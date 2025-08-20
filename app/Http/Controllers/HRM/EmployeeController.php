<?php

namespace App\Http\Controllers\HRM;

use Exception;
use App\Models\User;
use App\Models\Module;

use App\Models\company;
use App\Models\roleAcl;
use App\Models\Employee;
use App\Models\Department;
use App\Models\TypeDetail;
use App\Models\UserModules;
use Illuminate\Support\Arr;
use App\Models\EmployeeRole;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\EmployeeCompany;
use App\Models\BusinessCustomer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UserBusinessCustomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$employees = Employee::where('is_active', 1)->get();
        $employees = Employee::orderBy('created_at', 'DESC')->get();
        if (view()->exists('modules.HRM.employee.index')) {
            return view('modules.HRM.employee.index', compact('employees'));
        }
        return abort(404);
    }


    public function view($id)
    {
        //$data = Employee::find($id);
        $data = Employee::findorfail($id);
        //view('modules.HRM.employee.modals.view');
        return view('modules.HRM.employee.modals.view', compact('data'));
        //return response()->json(['status' => 200, 'category' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies      = company::where('status', 1)->get();
        $departments    = Department::where('is_active', 1)->get();
        //$teamLeads      = Employee::whereIn('designation', ['Assistant Manager / Team Lead', 'Manager'])->where('is_active', 1)->get();
        $teamLeads      = Employee::whereIn('designation', ['Assistant Manager / Team Lead'])->where('is_active', 1)->get(); //, 'Manager'
        $designations   = TypeDetail::where('type_id', 4)->where('status', 2)->get();
        $modules        = Module::where('is_active', 1)->get();
        $businessCustomers = BusinessCustomer::where('is_active', 1)->get(); // Fetch active business customers
        if (view()->exists('modules.HRM.employee.create')) {
            return view('modules.HRM.employee.create', compact('companies', 'departments', 'teamLeads', 'designations', 'modules', 'businessCustomers'));
        }
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        //dd($request->all());
        DB::beginTransaction();
        try {

            $data = $request->all();
            //echo '<pre>'; print_r($data); echo '</pre>'; //exit;
            $employee_data = Arr::except($data, ['companies', 'mis_password', 'user_modules']);

            $designation_array = explode("__", $data['designation']);

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $emp_picture = $employee_data['code'] . "." . $file->getClientOriginalExtension();
                $file->move('images/uploads/Employee/', $emp_picture);
                $employee_data['picture'] = $emp_picture;
            }

            $companies_data         = data_get($data, 'companies');

            $employee_data['designation'] = $designation_array[1];

            $employee = Employee::create($employee_data);
            //echo $employee->id;
            $emp_id = $employee->id;


            if ($request->has('companies')) {
                foreach ($companies_data as $com_key => $company_data_value) {

                    $com_data['employee_id'] = $emp_id;
                    $com_data['company_id'] = $company_data_value;
                    $com_data['is_active'] = 1;

                    EmployeeCompany::create($com_data);
                }
            } else {
                throw new Exception("Company Details Are Missing", 1);
            }

            if ($request->mis_password) {

                //echo '<pre>'; print_r($emp_data); echo '</pre>'; exit;
                // Validate the form data
                $request->validate([
                    //'employee_id' => 'exists:employees,id',
                    //'password' => 'required|string|confirmed|min:8',
                    'mis_password' => 'required|string|min:8',
                ]);
                // exit;
                // Create a new user
                $user = new User([
                    'employee_id' => $emp_id,
                    'name' => $employee->name,
                    'email' => $employee->official_email,
                    'password' => Hash::make($request->mis_password), //bcrypt($request->password),
                    'avatar' => $employee->picture,
                    'role' => $designation_array[0],
                    'is_active' => 1,
                    'created_by' => Auth::id(),
                ]);

               $user->save();

                // Get the last saved user ID
                $lastSavedUserId = $user->id;

                $today = Carbon::now();
                $next15Days = $today->addDays(15);
                foreach ($request->user_modules as $um_key => $um_value) {
                    $user_modules = new UserModules([
                        'user_id' => $lastSavedUserId,
                        'module_id' => $um_value,
                        'user_module_level' => 1,
                        'access_type' => ($request->employment_type == 'Permanent') ? "2" : "1",
                        'access_to_date' => $next15Days,
                        'created_by' => Auth::id(),
                        'is_active' => 1,
                        'status' => 1,
                    ]);
                    $user_modules->save();
                }

                /**
                 * Updated on 21-06-2025
                 * Added Business Customers
                 */

                if ($request->has('business_customers')) {
                    foreach ($request->business_customers as $bc_key => $business_customer_id) {

                        $bc_data['user_id'] = $lastSavedUserId;
                        $bc_data['business_customer_id'] = $business_customer_id;
                        $bc_data['is_active'] = 1;
                        $bc_data['created_by'] = Auth::id();

                        UserBusinessCustomer::create($bc_data);
                    }
                }

                /**
                 * End of Update on 21-06-2025
                */
            }


            DB::commit();

            return redirect()->route('hrm.employee-register')->with('success', 'Employee Created Successfully With Employee Number: ' . $employee->code);
        } catch (Exception $e) {
            DB::rollback();
            //$message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            return redirect()->route('hrm.employee-register')->with('error', $message)->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        //$data = Employee::findorfail($id);
        $data = Employee::with(['companies'])->findorfail($id);
        //dd($data);
        $companies      = company::where('status', 1)->get();
        $departments    = Department::where('is_active', 1)->get();
        $teamLeads      = Employee::whereIn('designation', ['Assistant Manager / Team Lead', 'Manager'])->where('is_active', 1)->get();
        $designations   = TypeDetail::where('type_id', 4)->where('status', 2)->get();
        $modules        = Module::where('is_active', 1)->get();
        $assignedCompanyIds = $data->companies->pluck('id')->toArray(); // Get the IDs of assigned companies
        $assignedModules = $data->user->modules;
        $assignedModuleIds = $data->user->modules->pluck('id')->toArray();

        $businessCustomers = BusinessCustomer::where('is_active', 1)->get(); // Fetch active business customers
        $assignedBusinessCustomerIds = $data->user->businessCustomers->pluck('id')->toArray();

        return view('modules.HRM.employee.modals.edit', compact('data', 'companies', 'departments', 'teamLeads', 'designations', 'modules', 'assignedCompanyIds', 'assignedModules', 'assignedModuleIds', 'businessCustomers', 'assignedBusinessCustomerIds'));
        //return view('modules.HRM.employee.modals.edit', compact('data', 'companies', 'departments', 'teamLeads', 'designations', 'modules', 'assignedCompanyIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        //return response()->json($request->all());
        DB::beginTransaction();

        try {
            $employee_id = $request->employee_id;
            // Validate the request data
            /* $request->validate([
                'name' => 'required|string|max:255',
                'official_email' => 'required|email|max:255|unique:employees,official_email,' . $employee->id,
                'password' => 'nullable|string|min:8|confirmed',
            ]); */

            $data = $request->all();
            //echo '<pre>'; print_r($data); echo '</pre>'; //exit;
            $employee_data = Arr::except($data, ['mis_password']);

            $designation_array = explode("__", $data['designation']);
            //$employee_data['designation'] = $designation_array[1];
            // Update existing passenger
            $employee = Employee::find($employee_id);
            $user = User::where('employee_id', $employee->id)->first();

            $emp_picture = '';

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $emp_picture = $employee_data['code'] . "." . $file->getClientOriginalExtension();
                $file->move('images/uploads/Employee/', $emp_picture);
                $employee->picture = $emp_picture;
                if ($user) {
                    $user->avatar = $emp_picture;
                }

            }


            $employee->code                 = $data["code"];
            $employee->title                = $data["title"];
            $employee->name                 = $data["name"];
            $employee->guardian_name        = $data["guardian_name"];
            $employee->personal_email       = $data["personal_email"];
            $employee->personal_phone       = $data["personal_phone"];
            $employee->cnic                 = $data["cnic"];
            $employee->gender               = $data["gender"];
            $employee->dob                  = $data["dob"];
            $employee->marital_status       = $data["marital_status"];
            $employee->education            = $data["education"];
            $employee->experience           = $data["experience"];
            $employee->religion             = $data["religion"];
            $employee->blood_group          = $data["blood_group"];
            $employee->city                 = $data["city"];
            $employee->state                = $data["state"];
            $employee->address              = $data["address"];
            $employee->zip_code             = $data["zip_code"];
            $employee->is_active            = (isset($data['is_active']) && $data['is_active']) == true ? 1 : 0;
            $employee->employment_type      = $data["employment_type"];
            $employee->designation          = $designation_array[1];
            $employee->department_id        = $data["department_id"];
            $employee->joining_date         = $data["joining_date"];
            $employee->basic_salary         = $data["basic_salary"];
            $employee->official_phone       = $data["official_phone"];
            $employee->official_email       = $data["official_email"];
            $employee->team_lead_id         = $data["team_lead_id"];
            $employee->updated_by           = Auth::user()->id;
            $employee->update();


            if ($user) {

                if ($request->mis_password) {
                    $user->password = Hash::make($request->mis_password);
                }

                $user->name = $data["name"];
                $user->role = $designation_array[0];
                $user->email = $data["official_email"];
                //$user->avatar = (!empty($emp_picture)) ? $emp_picture : "";
                $user->is_active = (isset($data['is_active']) && $data['is_active']) == true ? 1 : 0;
                $user->updated_by = Auth::user()->id;
                $user->update();
            }

            // Sync the selected companies
            if ($request->has('companies')) {
                $employee->companies()->sync($request->input('companies'));
            } else {
                // If no companies are selected, detach all companies
                $employee->companies()->detach();
            }

            // Check if the employee has a user record associated with them
            if ($employee->user) {
                $lastSavedUserId = $employee->user->id;
                $today = Carbon::now();
                $next15Days = $today->addDays(15);

                // Prepare an array to store the modules with their additional data
                $userModulesData = [];

                // Check if any modules are selected
                if ($request->has('user_modules')) {
                    foreach ($request->user_modules as $um_key => $module_id) {
                        // Prepare the additional fields for the pivot table
                        $userModulesData[$module_id] = [
                            'user_id' => $lastSavedUserId,
                            'module_id' => $module_id,
                            'user_module_level' => 1,
                            'access_type' => ($request->employment_type == 'Permanent') ? "2" : "1",
                            'access_to_date' => $next15Days,
                            'created_by' => Auth::id(),
                            'is_active' => 1,
                            'status' => 1,
                        ];
                    }

                    // Sync the modules with the additional data to the pivot table
                    $employee->user->modules()->sync($userModulesData);
                } else {
                    // If no modules are selected, detach all modules
                    $employee->user->modules()->detach();
                }

                // Prepare an array to store the modules with their additional data
                $userBusinessCustomer = [];

                // Check if any business customers are selected
                if ($request->has('business_customers')) {
                    foreach ($request->business_customers as $um_key => $business_customer_id) {
                        // Prepare the additional fields for the pivot table
                        $userBusinessCustomer[$business_customer_id] = [
                            'user_id' => $lastSavedUserId,
                            'business_customer_id' => $business_customer_id,
                            'created_by' => Auth::id(),
                            'is_active' => 1,
                        ];
                    }

                    // Sync the business customers with the additional data to the pivot table
                    $employee->user->businessCustomers()->sync($userBusinessCustomer);
                } else {
                    // If no business customers are selected, detach all
                    $employee->user->businessCustomers()->detach();
                }
            }

            DB::commit();
            return redirect()->route('hrm.all-emp')->with('success', 'Employee Updated Successfully');
            //return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Employee updated successfully'], 200);

        } catch (Exception $e) {
            DB::rollback();
            //$message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return redirect()->route('hrm.all-emp')->with('error', $message)->withInput();
            //return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }

    public function createEmployeeUser($id)
    {

        $emp_data = Employee::find($id);
        if (!$emp_data) {
            abort(404);
        }

        $user_data = $emp_data->user;
        if ($user_data) {
            abort(404);
            //return redirect()->route('hrm.all-emp')->with('warning', 'User Already Registered');
        }

        return view('modules.HRM.employee.modals.create-user', compact('emp_data'));
        //return view('employees.modals.create-user', compact('emp_data'));
    }

    public function saveEmployeeUser(Request $request)
    {
        //echo bcrypt($request->password);
        //dd($request->all());
        // DB::beginTransaction();
        // try {
        $emp_id = $request->employee_id;
        $emp_data = Employee::find($emp_id);

        //dd($emp_data);

        if (!$emp_data) {
            abort(404);
        }
        $user_data = $emp_data->user;
        if ($user_data) {
            //abort(404);
            return redirect()->route('hrm.all-emp')->with('warning', 'User Already Registered');
        }
        //echo '<pre>'; print_r($emp_data); echo '</pre>'; exit;
        // Validate the form data
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'password' => 'required|string|confirmed|min:8',
        ]);
        // exit;
        // Create a new user
        $user = new User([
            'employee_id' => $emp_id,
            'name' => $emp_data->name,
            'email' => $emp_data->official_email,
            'password' => Hash::make($request->password),//bcrypt($request->password),
            'avatar' => $emp_data->picture,
            'created_by' => Auth::id(),
        ]);

        $user->save();

        //$data = Employee::all();
        //return view('employees.index', compact('data'))->with('success', 'Employee Registered Successfully');
        return redirect()->route('hrm.all-emp')->with('success', 'User Registered Successfully');
        //return redirect()->route('all-employees')->with('success', 'Employee Registered Successfully');
        //return view('employees.all-employees');
        // } catch (Exception $e) {
        //     DB::rollback();
        //     //$message = "";
        //     $message = " Error Code: " . $e->getCode();
        //     $message .= " \n Line No: " . $e->getLine();
        //     $message .= "\n Error Message: " . $e->getMessage();
        //     return redirect()->route('all-employees')->with('error', $message)->withInput();
        // }
    }

    /**
     * Added on 21-06-2025
     */

     public function employeeRole()
    {
        $employeeRoles = EmployeeRole::where('is_active', 1)->get();
        return view('modules.HRM.employee.roles', compact('employeeRoles'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function saveEmployeeRole(Request $request)
    {
        //dd($request->all());
        DB::beginTransaction();
        try {

            $data = $request->all();
            $data['created_by'] = Auth::id();
            EmployeeRole::create($data);
            DB::commit();
            return redirect()->route('hrm.employee-role')->with('success', 'Role Created Successfully');
        } catch (Exception $e) {
            DB::rollback();
            //$message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            return redirect()->route('hrm.employee-role')->with('error', $message)->withInput();
        }
    }

    public function roleAclList($role_id)
    {
        $role = EmployeeRole::findOrFail($role_id);
        $modules = Module::all();
        return view('modules.HRM.employee.role-acl-list', compact('role', 'modules'));
    }

    public function saveRoleAcl(Request $request)
    {
        // dd($request->all());
        try {
            // Check if records exist for this user and module
            $moduleId = $request->module;
            $roleId = $request->role_id;
            // Fetch the module details from the database
            $module = Module::find($moduleId);

            if (!$module) {
                return redirect()->back()->with('error', 'Module not found.');
            }

            // Include the module prefix in the insert data
            $modulePrefix = $module->module_link;
            // Delete existing records
            roleAcl::where([
                'module_id' => $moduleId,
                'role_id' => $roleId
            ])->delete();

            // Insert new records
            $insertData = [];
            foreach ($request->routes as $route) {
                $routeParts = explode('__', $route);
                $insertData[] = [
                    'module_id' => $moduleId,
                    'module_prefix' => $modulePrefix,
                    'role_id' => $roleId,
                    'role_name' => $request->role,
                    'menu_name' => $routeParts[0],
                    'url_name' => $routeParts[1],
                    'url' => $routeParts[2],
                    'is_active' => true,
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            roleAcl::insert($insertData);

            return redirect()->back()->with('success', 'Access control updated successfully');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error updating access control: ' . $e->getMessage());
        }
    }

}
