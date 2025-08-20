<?php

namespace App\Http\Controllers\CRM;

use Exception;
use App\Models\User;
use App\Models\Inquiry;
use App\Models\Employee;
use App\Models\InquiryLog;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\InquiryAssigment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\InquiryAssigmentAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Exports\CRM\Inquiry\InquiriesExport;

class InquiryController extends Controller
{
    public $today;
    public $todayDateTime;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->today = Carbon::today();
        $this->todayDateTime = Carbon::now();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request) {
        if (view()->exists('modules.CRM.inquiry.list')) {

            // Get the logged-in user
            $user = Auth::user();
            //echo '<pre>'; print_r($user->employee); echo '</pre>'; //exit;
            if ($user->employee) {
                // Retrieve companies assigned to the employee
                $assignedCompanies = $user->employee->companies;

                //echo '<pre>'; print_r($assignedCompanies); echo '</pre>'; //exit;
            } else {
                //echo "ELSE";
                return abort(404);
            }

            return view('modules.CRM.inquiry.list', compact('assignedCompanies'));
        }
        return abort(404);
    }

    public function root() {
        return view('modules.CRM.index');
    }

    public function createInquiry() {

        if (view()->exists('modules.CRM.inquiry.create')) {

            // Get the logged-in user
            $user = Auth::user();
            //echo '<pre>'; print_r($user->employee); echo '</pre>'; //exit;
            if ($user->employee) {
                // Retrieve companies assigned to the employee
                $assignedCompanies = $user->employee->companies;

                //echo '<pre>'; print_r($assignedCompanies); echo '</pre>'; //exit;
            } else {
                //echo "ELSE";
                return abort(404);
            }

            return view('modules.CRM.inquiry.create', compact('assignedCompanies'));
        }
        return abort(404);

    }

    public function saveInquiry(Request $request) {


        //dd($request->all());
        DB::beginTransaction();
        try {
            $data = $request->all();
            $company_data           = explode('__', $data['company']);
            $company_id             = $company_data[0];
            $company_name           = $company_data[1];
            $source                 = $data['source'];
            $departure_airport      = isset($data['departure_from']) ? $data['departure_from'] : null;
            $departure_date         = isset($data['departure_date']) ? $data['departure_date'] : null;
            $arrival_airport        = isset($data['destination']) ? $data['destination'] : null;
            $arrival_date           = isset($data['return_date']) ? $data['return_date'] : null;
            $lead_passenger_name    = isset($data['full_name']) ? $data['full_name'] : null;
            $email                  = isset($data['email']) ? $data['email'] : null;
            $contact_number         = isset($data['phone_number']) ? $data['phone_number'] : null;
            $best_time_to_contact   = isset($data['best_time_to_call']) ? $data['best_time_to_call'] : null;
            $created_at             = isset($data['inquiry_date_time']) ? $data['inquiry_date_time'] : Carbon::now();

            $user = Auth::user();
            $inquiry_assignment_status = 1;
            $inquiry_assignment_on = null;
            $inquiry_assigned_to = null;

            if ($user->role == 5) {
                # assign inqyiry automatically
                $inquiry_assignment_status = 2;
                $inquiry_assignment_on = $this->todayDateTime;
                $inquiry_assigned_to = $user->id;
            }

            //$inquiry = Inquiry::where('contact_number', $contact_number)->orWhere('email', $email)->count();
            $query = Inquiry::where('contact_number', $contact_number);

            if (!is_null($email)) {
                $query->orWhere('email', $email);
            }

            $inquiryCount = $query->count();

            if ($inquiryCount > 0) {
                throw new Exception("Please try again, duplicate record found...", 1);
            }
            $message = "Inquiry added successfully";
            $inquiry = new Inquiry([
                'company_id'                => $company_id,
                'company_name'              => $company_name,
                'source'                    => $source,
                'departure_airport'         => $departure_airport,
                'departure_date'            => $departure_date,
                'arrival_airport'           => $arrival_airport,
                'arrival_date'              => $arrival_date,
                'lead_passenger_name'       => $lead_passenger_name,
                'email'                     => $email,
                'contact_number'            => $contact_number,
                'best_time_to_contact'      => $best_time_to_contact,
                'inquiry_assignment_status' => $inquiry_assignment_status,
                'inquiry_assignment_on'     => $inquiry_assignment_on,
                'inquiry_assigned_to'       => $inquiry_assigned_to,
                'created_at'                => $created_at,
                'created_by'                => $user->id,
                'status'                    => 1,
            ]);

            $inquiry->save();
            //dd($inquiry->id);
            if ($user->role == 5) {
                $inquiry_assigments = new InquiryAssigment([
                    'agent_id'              => $user->id,
                    'inquiry_id'            => $inquiry->id,
                    'comments'              => 'Automated Self Assigned Inquiry',
                    'assigned_by'           => $user->id,
                    'assigend_on'           => $this->todayDateTime,
                    'created_at'            => $this->todayDateTime,
                    'status'                => 1,
                ]);
                $inquiry_assigments->save();

                $message .= ", and assigned to you automatically...";
            }

            DB::commit();
            // Return success response
            return redirect()->route('crm.create-inquiry')->with('success', $message);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return redirect()->route('crm.create-inquiry')->with('error', $message)->withInput();
        }
    }

    public function inquiryList() {

        if (view()->exists('modules.CRM.inquiry.list')) {
            // Get the logged-in user
            $user = Auth::user();

            if ($user->employee) {
                // Retrieve companies assigned to the employee
                $assignedCompanies = $user->employee->companies;
            } else {
                //echo "ELSE";
                return abort(404);
            }

            $user_role = $user->role;
            //echo '<pre>'; print_r($user); echo '</pre>'; //exit;
            if ($user_role == 1 || $user_role == 2) {

                //$agents = User::where('role','>','2')->get();
                $agents = User::all();
            } else if ($user_role == 3 || $user_role == 4) {

                // Retrieve company IDs of the logged-in user
                $employeeCompanyIds = $assignedCompanies->pluck('id')->toArray();
                //echo '<pre>'; print_r($employeeCompanyIds); echo '</pre>'; //exit;
                // Check if user is role 4 (Team Lead)
                //echo $user->employee->id;
                if ($user_role == 4) {
                    // Retrieve the list of employees where the team_lead_id is the logged-in user
                    $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->id)->pluck('id')->toArray();
                    //echo '<pre>'; print_r($teamLeadEmployeeIds); echo '</pre>'; //exit;
                    // Get users (agents) that are assigned to this team lead and role > 2
                    $agents = User::where(function ($query) use ($teamLeadEmployeeIds, $user) {
                        // Include the team lead's own record (if needed)
                        $query->whereIn('employee_id', $teamLeadEmployeeIds)
                            ->orWhere('id', $user->id);  // Include the team lead's own bookings
                    })
                    //->where('role', '=', 5) // Only retrieve users with role == 5
                    ->get();

                    //echo '<pre>'; print_r($agents); echo '</pre>'; //exit;
                } else {
                    // For role 3, get all agents related to their company
                    $agents = User::whereHas('employee.companies', function ($query) use ($employeeCompanyIds) {
                        $query->whereIn('companies.id', $employeeCompanyIds);
                    })->where('role', '>', 2)->get();
                }
            } else {
                $agents[] = $user;
            }

            // echo '<pre>'; print_r($agents); echo '</pre>'; //exit;
            return view('modules.CRM.inquiry.list', compact('assignedCompanies', 'agents'));
        }
        return abort(404);
    }

    public function poolInquiryList(Request $request) {

        if (view()->exists('modules.CRM.inquiry.pool-list')) {

            $user = Auth::user();

            $user_role = $user->role;

            if ($user->employee) {
                // Retrieve companies assigned to the employee
                $assignedCompanies = $user->employee->companies;
                $employeeCompanyIds = $assignedCompanies->pluck('id')->toArray();

                if ($user_role == 1 || $user_role == 2) {
                    // Role 1 and 2: Super admin or Admin - Show all users
                    $agents = User::all();
                } else if ($user_role == 3 || $user_role == 4) {
                    // Retrieve company IDs of the logged-in user
                    $employeeCompanyIds = $assignedCompanies->pluck('id')->toArray();

                    // Check if user is role 4 (Team Lead)
                    if ($user_role == 4) {
                        // Retrieve the list of employees where the team_lead_id is the logged-in user
                        $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->id)->pluck('id')->toArray();

                        // Get users (agents) that are assigned to this team lead and role > 2
                        $agents = User::where(function ($query) use ($teamLeadEmployeeIds, $user) {
                            $query->whereIn('employee_id', $teamLeadEmployeeIds)
                                ->orWhere('id', $user->id);  // Include the team lead's own bookings
                        })->where('role', '=', 5) // Only retrieve users with role == 5
                        ->get();
                    } else {
                        // For role 3, get all agents related to their company
                        $agents = User::whereHas('employee.companies', function ($query) use ($employeeCompanyIds) {
                            $query->whereIn('companies.id', $employeeCompanyIds);
                        })->where('role', '>', 2)->get();
                    }
                } else {
                    // Other roles: Show only the logged-in user
                    $agents[] = $user;
                }

            } else {
                //echo "ELSE";
                return abort(404);
            }


            $inquiriesQuery = Inquiry::where('is_pooled', 1);
            // Apply company_id condition
            if (!isset($request->company_id) || $request->company_id === 'bank') {
                // Only show inquiries for assigned companies when 'company_id' is not posted or is 'bank'
                if (isset($assignedCompanies)) {
                    $inquiriesQuery->whereIn('company_id', $employeeCompanyIds);
                }
            } else {
                // If 'company_id' is specified in the search, filter by it
                $inquiriesQuery->where('company_id', '=', $request->company_id);
            }
            $inquiries = $inquiriesQuery->with('inquiryAssigments')->orderBy('id', 'DESC')->paginate(100)->appends($request->only([ 'company_id' ]));

            return view('modules.CRM.inquiry.pool-list', compact('inquiries', 'agents', 'assignedCompanies'));
        }
        return abort(404);
    }

    public function poolPickupInquiry($inquiry_id) {
        //return response()->json(['title' => 'Success', 'icon' => 'success', 'message' => 'this functionality is in development mode...'], 403);
        DB::beginTransaction();
        try {

            $inquiry = Inquiry::where('id', $inquiry_id)->where('is_pooled', 1)->first();
            // return response()->json(['title' => 'Success', 'icon' => 'success', 'message' => 'this functionality is in development mode...'], 403);
            if (!$inquiry) {
                //return response()->json(['message' => 'Inquiry Already Pickedup.'], 403);
                throw new Exception("Inquiry Already Pickedup", 1);

            }
            // Get the logged-in user's ID
            $user = Auth::user(); //->employee->companies->pluck('id');
            $userEmployee = $user->employee;
            $company_ids = $userEmployee->companies->pluck('id')->toArray();

            $userId = $user->id;
            $company_id = $inquiry->company_id;
            $inquiry_assigned_to = $inquiry->inquiry_assigned_to;
            $comments = "Pool Inquiry Pickuped";

            $inquiry_id             = $inquiry->id;
            $agent_id               = $userId;

            if (!in_array($company_id, $company_ids)) {
                throw new Exception("You can not pickup the inquiry of other company", 1);
            }

            if ($agent_id == $inquiry_assigned_to) {
                throw new Exception("You can not pickup same inquiry again.", 1);
            }

            $count = Inquiry::assignedPooledInquiriesCount($userId);
            if ($count >= 10) {
                throw new Exception("You can not pick more then 10 inquiries from pool..", 1);
            }

            $inquiryAssigment = InquiryAssigment::where('inquiry_id', $inquiry_id)->where('agent_id', $inquiry_assigned_to)->first();

            //$inquiryAssigment                   = InquiryAssigment::find($inquiry_assigment_id);
            $inquiryAssigment->agent_id         = $agent_id;
            $inquiryAssigment->comments         = 'Pool Inquiry, '.$comments;
            $inquiryAssigment->assigned_by      = $userId;
            $inquiryAssigment->assigend_on      = $this->todayDateTime;
            $inquiryAssigment->status           = 2;

            $inquiryAssigment->update();

            $logDescription = "Inquiry Reassigned to user from pool:".User::find($agent_id)->name.". Previouly was assigned to:".User::find($inquiry->inquiry_assigned_to)->name.". Previouly was assigned on:".$inquiry->inquiry_assignment_on;

            $inquiryLog = new InquiryLog([
                'inquiry_id'    => $inquiry_id,
                'action'        => 'Inquiry Pool Pickedup',
                'description'   => $logDescription,
                'created_by'    => $userId,
                'created_at'    => $this->todayDateTime,

            ]);
            $inquiryLog->save();

            $inquiry->is_pooled = false;
            $inquiry->is_pooled_at = $this->todayDateTime;
            $inquiry->inquiry_assignment_on = $this->todayDateTime;
            $inquiry->inquiry_assigned_to = $agent_id;
            $inquiry->update();

            DB::commit();
            return response()->json(['title' => 'Success', 'icon' => 'success', 'message' => 'Inquiry picked up successfully from pool.'], 200);
        } catch (Exception $e) {
            DB::rollback();
            //$message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            return response()->json(['message' => $message], 403);
        }
    }

    public function inquirySearch(Request $request, $return_type ='view')
    {
        // Remove all blank values from the request
        $searchParams = array_filter($request->all());
        $searchParams = Arr::except($searchParams, ['_token']);

        if (empty($searchParams)) {
            //echo "empty search";
            return Redirect::back()->with('error', 'Please provide at least one search parameter.');
        }

        $user = Auth::user();
        $user_role = $user->role;

        // If the user has an employee relationship, retrieve assigned companies
        if ($user->employee) {
            $assignedCompanies = $user->employee->companies;
        }

        if ($user_role == 1 || $user_role == 2) {
            // Role 1 and 2: Super admin or Admin - Show all users
            $agents = User::all();
        } else if ($user_role == 3 || $user_role == 4) {
            // Retrieve company IDs of the logged-in user
            $employeeCompanyIds = $assignedCompanies->pluck('id')->toArray();

            // Check if user is role 4 (Team Lead)
            if ($user_role == 4) {
                // Retrieve the list of employees where the team_lead_id is the logged-in user
                //$teamLeadEmployeeIds = Employee::where('team_lead_id', $user->id)->pluck('id')->toArray();
                $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->id)->pluck('id')->toArray();
                // echo '<pre>'; print_r($teamLeadEmployeeIds); echo '</pre>'; //exit;
                // Get users (agents) that are assigned to this team lead and role > 2
                $agents = User::where(function ($query) use ($teamLeadEmployeeIds, $user) {
                    $query->whereIn('employee_id', $teamLeadEmployeeIds)
                        ->orWhere('id', $user->id);  // Include the team lead's own bookings
                })
                //->where('role', '=', 5) // Only retrieve users with role == 5
                ->get();
            } else {
                // For role 3, get all agents related to their company
                $agents = User::whereHas('employee.companies', function ($query) use ($employeeCompanyIds) {
                    $query->whereIn('companies.id', $employeeCompanyIds);
                })->where('role', '>', 2)->get();
            }
        } else {
            // Other roles: Show only the logged-in user
            $agents[] = $user;
        }

        // Handle the logic for different user roles
        if (in_array($user_role, [1, 2, 3])) {
            // Role 1, 2, 3: Show all inquiries
            $inquiriesQuery = Inquiry::query();
        } elseif ($user_role == 4) {
            // Role 4: Team Lead - Show inquiries created by team members or the team lead themselves
            $requestedStatus = $request->get('inquiry_assignment_status');
            // If no status filter is selected, default to pending (1) and assigned (2)
            if (!$requestedStatus) {
                $inquiryStatuses = [1, 2];
            } else {
                // If a specific status is selected (pending or assigned), use it in the query
                $inquiryStatuses = [$requestedStatus];
            }

            $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->id)->pluck('id')->toArray();
            $teamLeadUserIds = User::whereIn('employee_id', $teamLeadEmployeeIds)->pluck('id')->toArray();


            /* $inquiriesQuery = Inquiry::where(function ($query) use ($teamLeadUserIds, $user) {
                $query->whereIn('inquiry_assigned_to', $teamLeadUserIds) // Inquiries by team members
                    ->orWhere('inquiry_assigned_to', $user->id); // Inquiries by the team lead
            }); */

            // Modify the query to show pending inquiries and assigned inquiries for the team lead and their team members
            $inquiriesQuery = Inquiry::where(function ($query) use ($teamLeadUserIds, $user) {
                // Pending inquiries
                $query->where('inquiry_assignment_status', 1) // Status 1 = Pending inquiries
                    // Assigned inquiries to team members or the team lead
                    ->orWhereIn('inquiry_assigned_to', $teamLeadUserIds) // Assigned to team members
                    ->orWhere('inquiry_assigned_to', $user->id); // Assigned to the team lead
            });


        } elseif ($user_role == 5) {
            // Role 5: Show only pending inquiries of companies assigned to the user and inquiries assigned to the user
            $employeeCompanyIds = $assignedCompanies->pluck('id')->toArray();
            $inquiriesQuery = Inquiry::whereIn('company_id', $employeeCompanyIds)
                ->where(function ($query) use ($user) {
                    $query->where('inquiry_assignment_status', '1') // Pending inquiries
                        ->orWhereHas('inquiryAssigments', function ($assignmentQuery) use ($user) {
                            $assignmentQuery->where('inquiry_assigned_to', $user->id); // Inquiries assigned to the user
                        });
                });
        }

        // Apply company_id condition
        if (!isset($searchParams['company_id']) || $searchParams['company_id'] === 'bank') {
            // Only show inquiries for assigned companies when 'company_id' is not posted or is 'bank'
            if (isset($assignedCompanies)) {
                $companyIds = $assignedCompanies->pluck('id')->toArray();
                $inquiriesQuery->whereIn('company_id', $companyIds);
            }
        } else {
            // If 'company_id' is specified in the search, filter by it
            $inquiriesQuery->where('company_id', '=', $searchParams['company_id']);
        }

        /** UPDATED ON 13-12-2024 */
        // Integrate inquiry_action filter
        if (isset($searchParams['inquiry_action'])) {
            $inquiriesQuery->whereHas('inquiryAssigments', function ($query) use ($searchParams) {
                $query->where('recent_status', $searchParams['inquiry_action']);
            });
        }
        /** END */
        // Apply the search filters to the inquiries query
        $inquiriesQuery->where(function ($query) use ($searchParams) {
            foreach ($searchParams as $field => $value) {
                if ($field === 'inquiry_from_date' || $field === 'inquiry_to_date') {
                    if ($field === 'inquiry_from_date') {
                        $query->whereDate('created_at', '>=', $value);
                    }
                    if ($field === 'inquiry_to_date') {
                        $query->whereDate('created_at', '<=', $value);
                    }
                }

                if ($field === 'inquiry_assigned_from_date' || $field === 'inquiry_assigned_to_date') {
                    if ($field === 'inquiry_assigned_from_date') {
                        $query->whereDate('inquiry_assignment_on', '>=', $value);
                    }
                    if ($field === 'inquiry_assigned_to_date') {
                        $query->whereDate('inquiry_assignment_on', '<=', $value);
                    }
                }

                if (in_array($field, ['source', 'lead_passenger_name', 'email', 'contact_number'])) {
                    $query->where($field, 'like', '%' . $value . '%');
                }

                if (in_array($field, ['company_id', 'inquiry_assignment_status', 'inquiry_assigned_to'])) {
                    $query->where($field, '=', $value);
                }
            }
        });

        if ($return_type == 'view') {

            $inquiries = $inquiriesQuery->with('inquiryAssigments')->orderBy('inquiry_assignment_on', 'DESC')->paginate(50)->appends($request->only([
                'company_id', 'source', 'inquiry_from_date', 'inquiry_to_date', 'inquiry_assignment_status',
                'inquiry_assigned_to', 'lead_passenger_name', 'email', 'contact_number',
                'inquiry_assigned_from_date', 'inquiry_assigned_to_date'
            ]));

        } else {
            // Fetch all inquiries without pagination for compact and JSON responses
            $inquiries = $inquiriesQuery->with('inquiryAssigments')->orderBy('id', 'DESC')->get();
        }
        if ($return_type == 'json') {
            return response()->json(compact('inquiries'));
        } elseif ($return_type == 'compact') {
            // Return the filtered data
            return $inquiries;
        } elseif ($return_type == 'view') {
            return view('modules.CRM.inquiry.list', compact('inquiries', 'assignedCompanies', 'searchParams', 'agents'));
        }
    }


    public function viewInquiry($inquiry_id) {

        if (view()->exists('modules.CRM.inquiry.modals.view')) {
            $inquiry = Inquiry::findOrFail($inquiry_id);
            return view('modules.CRM.inquiry.modals.view', compact('inquiry'));
        }
        return abort(404);
    }

    public function pickupInquiry($inquiry_id) {
        DB::beginTransaction();
        try {

            $inquiry = Inquiry::where('id', $inquiry_id)->where('inquiry_assignment_status', 1)->first();

            if (!$inquiry) {
                return response()->json(['message' => 'Inquiry Already Assigned.'], 403);
            }

            $company_id = $inquiry->company_id;
            $comments = "Self Assigned Inquiry";
            $inquiry_quota_check = ($company_id == 6) ? 1 : 2;

            // Get the logged-in user's ID
            $userId = Auth::id();

            // Query the database to get the total number of inquiries assigned to the user today
            $inquiriesCount = InquiryAssigment::where('agent_id', $userId)->whereDate('assigend_on', $this->today)->count();
            if ($inquiriesCount >= $inquiry_quota_check) {
                return response()->json(['message' => 'Sorry! Inquery quota is full, Please contact to administrator for assigning inquries.'], 403);
            }

            $inquiryAssigment = new InquiryAssigment([
                'agent_id' => $userId,
                'inquiry_id' => $inquiry->id,
                'comments' => $comments,
                'assigned_by' => $userId,
                'assigend_on' => $this->todayDateTime,
                'status' => 2,
            ]);
            $inquiryAssigment->save();

            $inquiry->inquiry_assignment_status = 2;
            $inquiry->inquiry_assignment_on = $this->todayDateTime;
            $inquiry->inquiry_assigned_to = $userId;
            $inquiry->update();

            DB::commit();
            return response()->json(['message' => 'Inquiry assigned successfully.'], 200);
        } catch (Exception $e) {
            DB::rollback();
            //$message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            return response()->json(['message' => $message], 403);
        }
    }

    public function viewInquiryAssignment($inquiry_id) {

        if (view()->exists('modules.CRM.inquiry.modals.inquiry-assignment')) {
            $inquiry = Inquiry::findOrFail($inquiry_id);
            // Fetch the company ID from the inquiry
            $companyId = $inquiry->company_id;


            $users = Employee::whereHas('companies', function ($query) use ($companyId) {
                $query->where('companies.id', $companyId);
            })
                ->whereHas('user', function ($query) {
                    $query->where('is_active', 1);
                })
                ->with(['user' => function ($query) {
                    $query->where('is_active', 1);
                }])
                ->get()
                ->pluck('user')
                ->filter();


            //echo '<pre>'; print_r($users); echo '</pre>'; //exit;
            return view('modules.CRM.inquiry.modals.inquiry-assignment', compact('inquiry', 'users'));
        }
        return abort(404);
    }

    public function saveInquiryAssignment(Request $request) {
        //return response()->json([$request->all(), Auth::user()]);
        DB::beginTransaction();
        try {

            $request->validate([
                'agent_id' => 'required',
                //'comments' => 'required|max:255',
            ]);

            $inquiry_id = $request->inquiry_id;
            $agent_id = $request->agent_id;
            $comments = isset($request->comments) ? $request->comments : null;

            $inquiry = Inquiry::where('id', $inquiry_id)->where('inquiry_assignment_status', 1)->first();

            if (!$inquiry) {
                return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => 'Inquiry already assigned to some other agent.'], 403);
            }

            $user = Auth::user();

            if ($user->role > 3) {
                return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => 'Only admin or manager can assign inquiry...'], 403);
            }

            $inquiryAssigment = new InquiryAssigment([
                'agent_id' => $agent_id,
                'inquiry_id' => $inquiry->id,
                'comments' => $comments,
                'assigned_by' => $user->id,
                'assigend_on' => $this->todayDateTime,
                'status' => 2,

            ]);
            $inquiryAssigment->save();

            $inquiry->inquiry_assignment_status = 2;
            $inquiry->inquiry_assignment_on = $this->todayDateTime;
            $inquiry->inquiry_assigned_to = $agent_id;
            $inquiry->update();

            DB::commit();
            return response()->json(['title' => 'Success', 'icon' => 'success', 'message' => 'Inquiry assigned successfully.'], 200);

        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            //$message = " Error Code: " . $e->getCode();
            //$message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            //return redirect()->route('crm.create-booking')->with('error', $message)->withInput();
            return response()->json(['code' => 400, 'title' => 'Warning', 'icon' => 'warning', 'message' => $message]);
        }
    }

    public function viewReInquiryAssignment($inquiry_id) {

        if (view()->exists('modules.CRM.inquiry.modals.inquiry-re-assignment')) {
            $inquiry = Inquiry::findOrFail($inquiry_id);

            // Fetch the company ID from the inquiry
            $companyId = $inquiry->company_id;

            $users = Employee::whereHas('companies', function ($query) use ($companyId) {
                $query->where('companies.id', $companyId);
            })
                ->whereHas('user', function ($query) {
                    $query->where('is_active', 1);
                })
                ->with(['user' => function ($query) {
                    $query->where('is_active', 1);
                }])
                ->get()
                ->pluck('user')
                ->filter();

            $inquiry_assigned = User::find($inquiry->inquiry_assigned_to);
            $inquiries_assigned_details = [
                'name' => $inquiry_assigned->name,
                'assigned_on' => $inquiry->inquiry_assignment_on,
            ];

            $inquiry_assigment = InquiryAssigment::where('inquiry_id', $inquiry_id)->where('agent_id', $inquiry->inquiry_assigned_to)->first();

            return view('modules.CRM.inquiry.modals.inquiry-re-assignment', compact('inquiry', 'users', 'inquiries_assigned_details', 'inquiry_assigment'));
        }
        return abort(404);
    }

    public function saveReInquiryAssignment(Request $request) {

        //return response()->json([$request->all(), Auth::user()]);

        DB::beginTransaction();
        try {

            $request->validate([
                'agent_id' => 'required',
                //'comments' => 'required|max:255',
            ]);

            $user = Auth::user();
            if ($user->role > 2) {
                throw new Exception("Only admin can re-assign inquiry...", 1);
            }

            $inquiry_id             = $request->inquiry_id;
            $inquiry_assigment_id   = $request->inquiry_assigment_id;
            $agent_id               = $request->agent_id;
            $comments               = isset($request->comments) ? $request->comments : null;

            $inquiry = Inquiry::where('id', $inquiry_id)->where('inquiry_assignment_status', 2)->first();

            if (!$inquiry) {
                throw new Exception("Inquiry assignment pending...", 1);
                //return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => 'Inquiry assignment pending.'], 403);
            }

            if ($agent_id == $inquiry->inquiry_assigned_to) {
                throw new Exception("You can not re-assign inquiry to same agent...", 1);
            }

            $inquiryAssigment = InquiryAssigment::find($inquiry_assigment_id);
            $inquiryAssigment->agent_id = $agent_id;
            $inquiryAssigment->comments = 'Re-Assigned, '.$comments;
            $inquiryAssigment->assigned_by = $user->id;
            $inquiryAssigment->assigend_on = $this->todayDateTime;
            $inquiryAssigment->status = 2;

            $inquiryAssigment->update();

            if ($inquiry->is_pooled === 1) {
                $logSubject = 'Inquiry Pool Re-Assignment';
                $logDescription = "Inquiry Reassigned to user from pool: ".User::find($agent_id)->name.". Previouly was assigned to: ".User::find($inquiry->inquiry_assigned_to)->name.". Previouly was assigned on:".$inquiry->inquiry_assignment_on;

                $inquiry->is_pooled = false;
                $inquiry->is_pooled_at = $this->todayDateTime;
            }else{
                $logSubject = 'Inquiry Re-Assignment';
                $logDescription = "Inquiry Reassigned to user:".User::find($agent_id)->name.". Previouly was assigned to:".User::find($inquiry->inquiry_assigned_to)->name.". Previouly was assigned on:".$inquiry->inquiry_assignment_on;
            }

            $inquiryLog = new InquiryLog([
                'inquiry_id'    => $inquiry_id,
                'action'        => $logSubject,
                'description'   => $logDescription,
                'created_by'    => $user->id,
                'created_at'    => $this->todayDateTime,

            ]);
            $inquiryLog->save();

            $inquiry->inquiry_assignment_status = 2;
            $inquiry->inquiry_assignment_on = $this->todayDateTime;
            $inquiry->inquiry_assigned_to = $agent_id;
            $inquiry->update();

            DB::commit();
            return response()->json(['title' => 'Success', 'icon' => 'success', 'message' => 'Inquiry re-assigned successfully.'], 200);

        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            //$message = " Error Code: " . $e->getCode();
            //$message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            //return redirect()->route('crm.create-booking')->with('error', $message)->withInput();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    public function viewInquiryCommunicate($inquiry_id, $assignment_id) {

        if (view()->exists('modules.CRM.inquiry.modals.view-inquiry-action')) {
            // $inquiry = Inquiry::findOrFail($inquiry_id);
            $inquiry = Inquiry::with('inquiryAssigments')->findOrFail($inquiry_id);
            return view('modules.CRM.inquiry.modals.view-inquiry-action', compact('inquiry'));
        }
        return abort(404);
    }

    public function saveInquiryAssignmentAction(Request $request) {
        //return $request->all();
        DB::beginTransaction();
        try {

            $inquiry_id = $request->inquiry_id;
            $inquiries_assignment_id = $request->inquiries_assignment_id;
            $comments = $request->comments;
            $inquiry_status = $request->inquiry_status;

            if ($inquiry_status == null) {
                throw new Exception("Please select an inqury action", 1);
            }

            $inquiry = Inquiry::findOrFail($inquiry_id);

            if ($inquiry->inquiry_assignment_status ==1 || $inquiry->inquiry_assigned_to != Auth::id()) {
                throw new Exception("This inquiry is not assigned to you. you can not update the communication status", 1);
            }

            $inquiries_assignment = InquiryAssigment::findOrFail($inquiries_assignment_id);

            if (!$inquiries_assignment) {
                throw new Exception("No inquiry assignment found!", 1);
            }

            $inquiries_assignment->recent_status = $inquiry_status;
            $inquiries_assignment->recent_status_on = $this->todayDateTime;

            $inquiries_assignment->update();

            $inquiry_assignment_action = new InquiryAssigmentAction([
                'inquiries_assignment_id'           => $inquiries_assignment_id,
                'inquiry_status'                    => $inquiry_status,
                'comments'                          => $comments,
                'created_by'                        => Auth::id(),
                'created_at'                        => Carbon::now(),
                'status'                            => 1,
            ]);

            $inquiry_assignment_action->save();

            $inquiry->is_pooled = false;
            $inquiry->update();

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Inquiry action added successfully'],200);
            //return redirect()->route('crm.create-booking')->with('success', 'Booking Generated Successfully With Booking Number:');
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            //return redirect()->route('crm.create-booking')->with('error', $message)->withInput();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 403);
        }
    }

    public function deleteInquiry(int $inquiry_id)
    {
        DB::beginTransaction();
        try {
            $userRole = Auth::user()->role;
            if ($userRole > 3) {
                return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => 'You are not authorised to perform this action...'], 403);
            }
            $inquiry = Inquiry::where('id', $inquiry_id)->where('inquiry_assignment_status', 1)->first();

            if (!$inquiry) {
                return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => 'Inquiry already assigned to agent.'], 403);
            }

            $inquiry->delete();

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Inquiry removed successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    // Export filtered bookings to Excel
    public function exportToExcel(Request $request)
    {
        $user = Auth::user();
        if ($user->role > 3) {
            return response()->json('Inavlid Request', 400);

        }
        // Call the bookingSearch method to get the filtered bookings
        // Pass 'compact' as the return_type to get the data
        $filteredBookings = $this->inquirySearch($request, 'compact');

        // Generate a temporary file name
        $fileName = 'inquiries_report_'.Auth::user()->id.'_'. \Carbon\Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Store the file in the storage/app/public directory
        Excel::store(new InquiriesExport($filteredBookings), 'public/ExcelInquiries/' . $fileName);

        // Generate the public URL for the stored file
        $downloadUrl = Storage::url('public/ExcelInquiries/'.$fileName);

        // Return the download URL as a JSON response
        return response()->json(['url' => $downloadUrl]);
    }

    public function viewBulkInquiryAssignment($inquiry_ids) {
        //echo $inquiry_ids;
        $inquiry_idss = explode(",", $inquiry_ids);
        if (view()->exists('modules.CRM.inquiry.modals.inquiry-bulk-assignment')) {
            $inquiries = Inquiry::whereIn('id', $inquiry_idss)->get();
            //echo '<pre>'; print_r($inquiries); echo '</pre>'; //exit;

            // Step 2: Extract unique company IDs from inquiries
            $company_ids = $inquiries->pluck('company_id')->unique();

            // Step 3: Get employees associated with those company IDs through the pivot table
            $employees = Employee::whereHas('companies', function ($query) use ($company_ids) {
                $query->whereIn('company_id', $company_ids);
            })->get();

            // Step 4: Get users associated with those employees
            $users = User::where('is_active', 1)->whereIn('employee_id', $employees->pluck('id'))->get();

            return view('modules.CRM.inquiry.modals.inquiry-bulk-assignment', compact('inquiry_ids', 'users'));
        }
        return abort(404);
    }

    public function saveBulkInquiryAssignment__old(Request $request) {
        //return response()->json([$request->all(), Auth::user()]);
        DB::beginTransaction();
        try {

            $user = Auth::user();

            if ($user->role > 3) {
                return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => 'Only admin or manager can assign inquiry...'], 403);
            }

            $request->validate([
                'agent_id' => 'required',
                'inquiry_ids' => 'required',
            ]);

            $inquiry_ids = $request->inquiry_ids;
            $agent_id = $request->agent_id;
            $comments = isset($request->comments) ? $request->comments : null;

            // Get the agent's user record and the associated employee
            $agent = User::findOrFail($agent_id);
            $employeeId = $agent->employee_id; // Assuming employee_id is stored in the users table

            $employee = Employee::with('companies')->findOrFail($employeeId);
            $agentCompanyIds = $employee->companies->pluck('id')->toArray();

            $inquiry_idss = explode(",", $inquiry_ids);
            $inquiries = Inquiry::whereIn('id', $inquiry_idss)->get();
            $assignedInquiryIds = []; // Array to hold successfully assigned inquiry IDs
            foreach ($inquiries as $key => $inquiry) {

                if ($inquiry->inquiry_assignment_status == 1 && in_array($inquiry->company_id, $agentCompanyIds)) {
                    $inquiryAssigment = new InquiryAssigment([
                        'agent_id' => $agent_id,
                        'inquiry_id' => $inquiry->id,
                        'comments' => $comments,
                        'assigned_by' => $user->id,
                        'assigend_on' => now(),//$this->todayDateTime,
                        'status' => 2,

                    ]);
                    $inquiryAssigment->save();

                    $inquiry->inquiry_assignment_status = 2;
                    $inquiry->inquiry_assignment_on = now();//$this->todayDateTime;
                    $inquiry->inquiry_assigned_to = $agent_id;
                    $inquiry->save(); // Call save() instead of update() for clarity
                    $assignedInquiryIds[] = $inquiry->id; // Collect assigned inquiry IDs
                }

            }

            DB::commit();
            return response()->json(['title' => 'Success', 'icon' => 'success', 'message' => count($assignedInquiryIds).' Inquiry assigned successfully.','inquiry_ids' => $assignedInquiryIds], 200);

        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            //$message = " Error Code: " . $e->getCode();
            //$message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            //return redirect()->route('crm.create-booking')->with('error', $message)->withInput();
            return response()->json(['code' => 400, 'title' => 'Warning', 'icon' => 'warning', 'message' => $message]);
        }
    }

    public function saveBulkInquiryAssignment(Request $request) {
        //return response()->json([$request->all(), Auth::user()]);
        DB::beginTransaction();
        try {

            $user = Auth::user();

            if ($user->role > 3) {
                return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => 'Only admin or manager can assign inquiry...'], 403);
            }

            $request->validate([
                'agent_id' => 'required',
                'inquiry_ids' => 'required',
            ]);

            $inquiry_ids = $request->inquiry_ids;
            $agent_id = $request->agent_id;
            $comments = isset($request->comments) ? $request->comments : null;

            // Get the agent's user record and the associated employee
            $agent = User::findOrFail($agent_id);
            $employeeId = $agent->employee_id; // Assuming employee_id is stored in the users table

            $employee = Employee::with('companies')->findOrFail($employeeId);
            $agentCompanyIds = $employee->companies->pluck('id')->toArray();

            $inquiry_idss = explode(",", $inquiry_ids);
            $inquiries = Inquiry::whereIn('id', $inquiry_idss)->get();

            $assignedInquiryIds = []; // Array to hold successfully assigned inquiry IDs
            foreach ($inquiries as $key => $inquiry) {

                $inquiry_assigned_to = $inquiry->inquiry_assigned_to;

                if (in_array($inquiry->company_id, $agentCompanyIds)) {

                    if ($inquiry->inquiry_assignment_status == 1) {
                        $inquiryAssigment = new InquiryAssigment([
                            'agent_id' => $agent_id,
                            'inquiry_id' => $inquiry->id,
                            'comments' => $comments,
                            'assigned_by' => $user->id,
                            'assigend_on' => now(),//$this->todayDateTime,
                            'status' => 2,

                        ]);
                        $inquiryAssigment->save();

                    }elseif ($inquiry->is_pooled == 1) {

                        $inquiryAssigment = InquiryAssigment::where('inquiry_id', $inquiry->id)->where('agent_id', $inquiry_assigned_to)->first();

                        //$inquiryAssigment                   = InquiryAssigment::find($inquiry_assigment_id);
                        $inquiryAssigment->agent_id         = $agent_id;
                        $inquiryAssigment->comments         = 'Pool Inquiry, '.$comments;
                        $inquiryAssigment->assigned_by      = $user->id;
                        $inquiryAssigment->assigend_on      = now(); //$this->todayDateTime;
                        $inquiryAssigment->status           = 2;

                        $inquiryAssigment->update();

                        $logDescription = "Inquiry Reassigned to user from pool:".User::find($agent_id)->name.". Previouly was assigned to:".User::find($inquiry->inquiry_assigned_to)->name.". Previouly was assigned on:".$inquiry->inquiry_assignment_on;

                        $inquiryLog = new InquiryLog([
                            'inquiry_id'    => $inquiry->id,
                            'action'        => 'Inquiry Pool Pickedup',
                            'description'   => $logDescription,
                            'created_by'    => $user->id,
                            'created_at'    => $this->todayDateTime,

                        ]);
                        $inquiryLog->save();

                        $inquiry->is_pooled = false;
                        $inquiry->is_pooled_at = $this->todayDateTime;

                    }
                    $assignedInquiryIds[] = $inquiry->id; // Collect assigned inquiry IDs
                    $inquiry->inquiry_assignment_status = 2;
                    $inquiry->inquiry_assignment_on = now();//$this->todayDateTime;
                    $inquiry->inquiry_assigned_to = $agent_id;
                    $inquiry->save(); // Call save() instead of update() for clarity
                }

            }

            DB::commit();
            return response()->json(['title' => 'Success', 'icon' => 'success', 'message' => count($assignedInquiryIds).' Inquiry assigned successfully.','inquiry_ids' => $assignedInquiryIds], 200);

        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            //$message = " Error Code: " . $e->getCode();
            //$message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            //return redirect()->route('crm.create-booking')->with('error', $message)->withInput();
            return response()->json(['code' => 400, 'title' => 'Warning', 'icon' => 'warning', 'message' => $message]);
        }
    }

}
