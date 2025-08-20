<?php

namespace App\Http\Controllers\CRM;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\bookingPayment;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CRM\BookingController;

class CrmHomeController extends Controller
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
        if (view()->exists('modules.CRM.index')) {

            $days = 7;

            /**
             * CLOSE TRAVELING BOOKINGS
             */
            $close_traveling_bookings = BookingController::closedTravelingBookings($request, 'compact', $days);
            // Decode the JSON response to access the data


            $sortedBookings = $close_traveling_bookings['sortedBookings'];
            $today = $close_traveling_bookings['today'];
            /**
             * END
             */

            /**
             * Ticket Deadline Report
             */

            $ticket_deadline_bookings = BookingController::ticketDeadlineBookings($request, 'compact', $days);
            $ticketDeadlineBookings = $ticket_deadline_bookings['ticketDeadlineBookings'];
            $nextDate = $ticket_deadline_bookings['nextDate'];

            $pending_installment_bookings = BookingController::pendingInstallmentBookings($request, 'compact', $days);
            $pendingInstallmentBookings = $pending_installment_bookings['pendingInstallmentBookings'];

            return view('modules.CRM.index', compact('sortedBookings', 'today', 'ticketDeadlineBookings', 'nextDate', 'pendingInstallmentBookings'));
        }
        return abort(404);
    }

    public function index(Request $request)
    {
        if (!view()->exists('modules.CRM.index')) {
            return abort(404);
        }

        $days = 7;

        // Use lazy loading only if needed, defer heavier operations
        $data = [
            'sortedBookings' => [],
            'today' => null,
            'ticketDeadlineBookings' => [],
            'nextDate' => null,
            'pendingInstallmentBookings' => [],
            'approvalPendingPayments' => [],
        ];

        // Load reports concurrently where possible
        try {
            // 1. Closed Traveling Bookings
            $closeTravel = BookingController::closedTravelingBookings($request, 'compact', $days);
            $data['sortedBookings'] = $closeTravel['sortedBookings'] ?? [];
            $data['today'] = $closeTravel['today'] ?? null;

            // 2. Ticket Deadline Bookings
            $ticketDeadline = BookingController::ticketDeadlineBookings($request, 'compact', $days);
            $data['ticketDeadlineBookings'] = $ticketDeadline['ticketDeadlineBookings'] ?? [];
            $data['nextDate'] = $ticketDeadline['nextDate'] ?? null;

            // 3. Pending Installment Bookings
            $installments = BookingController::pendingInstallmentBookings($request, 'compact', $days);
            $data['pendingInstallmentBookings'] = $installments['pendingInstallmentBookings'] ?? [];

            // 4. Approval Pending Payments for users with role <= 2 and department = 'accounts'
            $user = Auth::user();
            if ($user->role <= 2) { // && $user->department === 'accounts'
                // Only select necessary columns and eager load booking id
                $data['approvalPendingPayments'] = bookingPayment::with(['booking']) // load only needed columns
                    ->where('is_approved', 0)
                    ->latest()
                    ->take(100) // Optional: limit records for performance
                    ->get();
            }elseif ($user->role >= 3) {
                // Agent/User: Show only their own bookings
                $data['approvalPendingPayments'] = bookingPayment::with(['booking'])
                    //->where('is_approved', 0)
                    ->whereHas('booking', function ($query) use ($user) {
                        $query->where('created_by', $user->id);
                    })
                    ->latest()
                    // ->take(100)
                    ->take(25)
                    ->get();
            }

            // dd($data['approvalPendingPayments']);

        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('CRM Dashboard Error: ' . $e->getMessage());
        }

        return view('modules.CRM.index', $data);
    }

    public function root()
    {
        return view('modules.CRM.index');
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function dashboard(Request $request)
    {
        if (view()->exists('modules.CRM.dashboard')) {
            return view('modules.CRM.dashboard');
        }
        return abort(404);
    }

}
