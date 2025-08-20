<?php

namespace App\Services;

use App\Models\User;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportFilterService
{
    /**
     * Get agent IDs based on the logged-in user role.
     *
     * @return array
     */
    public function getAgentIds()
    {
        $user = Auth::user();
        $agents = collect(); // Default empty collection

        if ($user->employee) {
            $assignedCompanies = $user->employee->companies;
        }

        $userRole = $user->role;

        if ($userRole == 1 || $userRole == 2) {
            // Admins can see all users
            $agents = User::all();
        } elseif ($userRole == 3 || $userRole == 4) {
            // Retrieve company IDs of the logged-in user
            $employeeCompanyIds = $assignedCompanies->pluck('id')->toArray();

            if ($userRole == 4) {
                // Retrieve employees under this team lead
                $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->employee->id)->pluck('id')->toArray();

                // Get users (agents) assigned to this team lead
                $agents = User::where(function ($query) use ($teamLeadEmployeeIds, $user) {
                    $query->whereIn('id', $teamLeadEmployeeIds)
                        ->orWhere('id', $user->id);
                })->get();
            } else {
                // For role 3, get all agents related to their company
                $agents = User::whereHas('employee.companies', function ($query) use ($employeeCompanyIds) {
                    $query->whereIn('companies.id', $employeeCompanyIds);
                })->where('role', '>', 2)->get();
            }
        } else {
            $agents = collect([$user]); // If not admin/team lead, return only self
        }

        return $agents->pluck('id')->toArray(); // Return only agent IDs
    }

    /**
     * Get default date filters (First day of the current month to today).
     *
     * @param $request
     * @return array
     */
    public function getDateFilters($request)
    {
        return [
            'from_date' => $request->input('from_date') ?? Carbon::now()->startOfMonth()->toDateString(),
            'to_date' => $request->input('to_date') ?? Carbon::now()->toDateString(),
        ];
    }

    /**
     * Get additional filters like company ID and booking number.
     *
     * @param $request
     * @return array
     */
    public function getAdditionalFilters($request)
    {
        return [
            'company_id' => $request->input('company_id') ?? null,
            'booking_number' => $request->input('booking_number') ?? null,
        ];
    }

    /**
     * Get assigned companies and agents based on the current user and provided agent IDs.
     *
     * @param array $agentIds
     * @return array
     */
    public function getAssignedCompaniesAndAgents(array $agentIds)
    {
        $user = Auth::user();

        // Retrieve assigned companies if the user has an employee record
        $assignedCompanies = $user->employee ? $user->employee->companies : collect();

        // Retrieve agents based on provided agent IDs
        $agents = User::whereIn('id', $agentIds)->get();

        return [
            'assignedCompanies' => $assignedCompanies,
            'agents' => $agents
        ];
    }
}
