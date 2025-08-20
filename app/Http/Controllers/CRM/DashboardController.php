<?php

namespace App\Http\Controllers\CRM;

use App\Models\User;
use App\Models\Booking;
use App\Models\company;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index__(Request $request)
    {
        if (view()->exists('modules.CRM.dashboard')) {
            // Base queries without predefined conditions
            $user = Auth::user();
            $currentMonthStart = now()->startOfMonth();
            $currentMonthEnd = now()->endOfMonth();

            // Base Queries
            $bookingsQuery = Booking::query();
            $agentsQuery = User::query();
            $companiesQuery = Company::query();

            // Add default date range filter for current month
            if (!$request->has('start_date') || !$request->has('end_date')) {
                $bookingsQuery->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd]);
            }

            // Fetch Data
            $bookings = $bookingsQuery->get();
            $agents = $agentsQuery->get();
            $companies = $companiesQuery->get();

            // Prepare Data for Graphs and Counts
            $counts = $this->calculateCounts($bookings);
            $supplierWiseSales = $this->supplierWiseSalesChart($bookings);
            $pieChart = $this->preparePieChart($bookings);

            // echo '<pre>';
            // print_r($supplierWiseSales);
            // echo '</pre>'; //exit;
            return view('modules.CRM.dashboard', compact('counts', 'supplierWiseSales'));

        }
        //return abort(404);
    }

    public function index(Request $request)
    {
        if (!view()->exists('modules.CRM.dashboard')) {
            return abort(404);
        }

        $user = Auth::user();
        $user_role = $user->role;

        if (!$user->employee) {
            return abort(404);
        }

        $assignedCompanies = $user->employee->companies;

        // Define base queries for filtering data
        $bookingsQuery = Booking::query();
        $agentsQuery = User::query();
        $companiesQuery = Company::query();

        // Apply role-based data visibility
        if ($user_role == 5) {
            // Agent can only see their own data
            $bookingsQuery->where('agent_id', $user->id);
            $agentsQuery->where('id', $user->id);
        } elseif ($user_role == 4) {
            // Team lead can view their data and their team's data
            $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->employee->id)->pluck('id')->toArray();
            $bookingsQuery->where(function ($query) use ($teamLeadEmployeeIds, $user) {
                $query->whereIn('agent_id', $teamLeadEmployeeIds)->orWhere('agent_id', $user->id);
            });
            $agentsQuery->whereIn('id', $teamLeadEmployeeIds)->orWhere('id', $user->id);
        } elseif (in_array($user_role, [1, 2])) {
            // Admin can view all data; optionally filter by company
            if ($request->filled('company_id')) {
                $companyId = $request->company_id;
                $bookingsQuery->whereHas('company', function ($query) use ($companyId) {
                    $query->where('id', $companyId);
                });
            }
        }

        // Apply additional filters from frontend
        if ($request->filled('agent_id')) {
            $bookingsQuery->where('agent_id', $request->agent_id);
        }

        if ($request->has(['start_date', 'end_date'])) {
            $bookingsQuery->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } else {
            $currentMonthStart = now()->startOfMonth();
            $currentMonthEnd = now()->endOfMonth();
            $bookingsQuery->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd]);
        }

        // Fetch filtered data
        $bookings = $bookingsQuery->get();
        $agents = $agentsQuery->where('role', '>', 2)->get();
        $companies = $companiesQuery->get();

        // Prepare data for view
        $counts = $this->calculateCounts($bookings);
        $supplierWiseSales = $this->supplierWiseSalesChart($bookings);
        $pieChart = $this->preparePieChart($bookings);

        return view('modules.CRM.dashboard', compact('counts', 'supplierWiseSales', 'agents', 'assignedCompanies', 'companies'));
    }

    protected function applyFilters($query, Request $request, $user)
    {
        $userRole = $user->role;

        if ($userRole == 5) { // Agent Role
            $query->where('id', $user->id);
        } elseif (in_array($userRole, [1, 2])) { // Admin Role
            if ($user->employee) {
                $assignedCompanyIds = $user->employee->companies->pluck('id')->toArray();

                // Adjusting query for agents under assigned companies
                $query->whereHas('employee.companies', function ($companyQuery) use ($assignedCompanyIds) {
                    $companyQuery->whereIn('companies.id', $assignedCompanyIds);
                });
            }
        }

        // Apply additional filters from request
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('agent_id')) {
            $query->where('id', $request->agent_id);
        }

        if ($request->filled('company_id')) {
            $query->whereHas('employee.companies', function ($companyQuery) use ($request) {
                $companyQuery->where('id', $request->company_id);
            });
        }
    }


    protected function calculateCounts($bookings)
    {
        return [
            'total_sales' => $bookings->count(),
            'total_sales_value' => $bookings->sum('total_sales_cost'),
            'total_deposits' => $bookings->sum('deposite_amount'),
            'total_balance_pending' => $bookings->sum('balance_amount'),
            'total_refunds' => $bookings->sum('refunded_amount'),
        ];
    }
    protected function supplierWiseSalesChart($bookings)
    {
        // Ensure the bookings have ticket_supplier data and group by it
        return $bookings->groupBy('ticket_supplier')->map(function ($group, $supplierName) {

            // Check if supplierName is available and not empty
            if (empty($supplierName)) {
                // If ticket_supplier is empty, assign a default or skip this group
                $supplierName = 'Unknown Supplier';
            }

            // Sum the total revenue from the bookings
            $totalRevenue = $group->sum('total_amount');

            // For each booking, get the associated net cost for pricing_type = "bookingFlight"
            $totalNetCost = $group->sum(function ($booking) {
                return $booking->prices->where('pricing_type', 'bookingFlight')->sum('net_total');
            });

            return [
                'ticket_supplier' => $supplierName,
                'total_booking' => $group->count(),
                'total_revenue' => $totalRevenue,
                'total_net_cost' => $totalNetCost,
            ];
        })->values();
    }

    protected function preparePieChart($bookings)
    {
        return $bookings->groupBy('status')->map(function ($group, $status) {
            return [
                'status' => $status,
                'total_sales' => $group->count(),
            ];
        })->values();
    }
}
