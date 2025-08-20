@extends('layouts.master-hrm')
@section('title')
    {{-- @lang('translation.hrm') --}}
    HRM
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboards
        @endslot
        @slot('title')
            HRM | Create New Employee
        @endslot
    @endcomponent

@section('css')
    <style>

    </style>
@endsection

<form action="{{ route('hrm.employee-save') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Personal Details</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('hrm.all-emp') }}"
                            class="btn btn-soft-success btn-sm float-end btn-label"><i
                                class="ri-numbers-line label-icon align-middle fs-16 me-2"></i> View Employees</a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="employee_code" class="form-label">Employee Code</label>
                                <input type="number" class="form-control" placeholder="Enter Employee Code" id="employee_code" name="code">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <select class="select2 form-control-sm" id="title" name="title">
                                    <option></option>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Miss.">Miss.</option>
                                    <option value="Mstr.">Mstr.</option>
                                </select>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" placeholder="Enter your full name" id="full_name" name="name">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="guardian_name" class="form-label">Guardian Name</label>
                                <input type="text" class="form-control" placeholder="Enter your Guardian Name" id="guardian_name" name="guardian_name">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <!-- Default File Input Example -->
                            <div class="mb-3">
                                <label for="employee_image" class="form-label">Emp. Image</label>
                                <input class="form-control" type="file" id="employee_image" name="picture">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="personal_email" class="form-label">Personal Email</label>
                                <input type="text" class="form-control" placeholder="Enter your personal email" id="personal_email" name="personal_email">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="personal_phone" class="form-label">Personal Phone</label>
                                <input type="text" class="form-control" placeholder="Enter your personal phone" id="personal_phone" name="personal_phone">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="cnic" class="form-label">CNIC</label>
                                <input type="text" class="form-control" placeholder="Enter your CNIC" id="cnic" name="cnic">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="select2 form-control-sm" id="gender" name="gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="text" class="form-control flatpickr-date" placeholder="Enter your DOB" id="dob" name="dob">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="marital_status" class="form-label">Marital Status</label>
                                <select class="select2 form-control-sm" id="marital_status" name="marital_status">
                                    <option value="0">Single</option>
                                    <option value="1">Married</option>
                                </select>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="education" class="form-label">Education</label>
                                <input type="text" class="form-control" placeholder="Enter education" id="education" name="education">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="experience" class="form-label">Experience</label>
                                <input type="text" class="form-control" placeholder="Enter experience" id="experience" name="experience">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="religion" class="form-label">Religion</label>
                                <select class="select2 mb-3" name="religion" id="religion" aria-label="Select Religion">
                                    <option value="Christianity">Christianity</option>
                                    <option value="Hinduism">Hinduism</option>
                                    <option value="Islam" selected>Islam</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="blood_group" class="form-label">Blood Group</label>
                                <select class="select2 mb-3" name="blood_group" id="blood_group" aria-label="Select Blood Group">
                                    <option></option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>
                        </div><!--end col-->
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" placeholder="Enter city" id="city" name="city">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" placeholder="Enter state" id="state" name="state">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" placeholder="Enter address" id="address" name="address">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="zip_code" class="form-label">Zip Code</label>
                                <input type="number" class="form-control" placeholder="Enter zip code" id="zip_code" name="zip_code">
                            </div>
                        </div><!--end col-->
                    </div>

                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Company Internal Details</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="employment_type" class="form-label">Employment Type</label>
                                <select class="select2 form-control-sm" id="employment_type" name="employment_type">
                                    <option value="Permanent">Permanent</option>
                                    <option value="Temp">Temp</option>
                                </select>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <select class="select2 mb-3" name="designation" id="designation" aria-label="Select designation">
                                    @foreach ($designations as $designation)
                                    <option value="{{ $designation->detail_number.'__'.$designation->name }}" {{ ($designation->detail_number == 5) ? "selected" : "" }}>{{ $designation->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select class="select2 mb-3" name="department_id" id="department" aria-label="Select department">
                                    <option></option>
                                    @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!--end col-->
                        {{-- <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="sub_department" class="form-label">Sub Department</label>
                                <select class="select2 mb-3" name="sub_department_id" id="sub_department" aria-label="Select sub department">
                                    <option></option>

                                </select>
                            </div>
                        </div> --}}<!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="team_lead_id" class="form-label">Manager / Team Lead </label>
                                <select class="select2 mb-3" name="team_lead_id" id="team_lead_id" aria-label="Manager / Team Lead">
                                    {{-- <option value="{{ $team_lead->id }}">{{ $team_lead->name }}</option> --}}
                                    <option></option>
                                    @foreach ($teamLeads as $team_lead)
                                    <option value="{{ $team_lead->user->id }}">{{ $team_lead->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="joining_date" class="form-label">Joining Date</label>
                                <input type="text" class="form-control flatpickr-date" placeholder="Enter Joining Date" id="joining_date" name="joining_date">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="basic_salary" class="form-label">Basic Salary</label>
                                <input type="number" class="form-control" placeholder="Enter Basic Salary" id="basic_salary" name="basic_salary" step="0.01">
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="companies" class="form-label">Company Names</label>
                                <select class="select2 mb-3" name="companies[]" id="companies" aria-label="Select Company Names" multiple>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="business_customers" class="form-label">Business Customers</label>
                                <select class="select2 mb-3 select2" name="business_customers[]" id="business_customers" aria-label="Select Business Customers" multiple>
                                    @foreach ($businessCustomers as $businessCustomer)
                                        <option value="{{ $businessCustomer->id }}">{{ $businessCustomer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="official_phone" class="form-label">Official Phone Number</label>
                                <input type="tel" class="form-control" placeholder="+(245) 451 45123" id="official_phone" name="official_phone">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="official_email" class="form-label">Official Email Address</label>
                                <input type="email" class="form-control" placeholder="example@gamil.com" id="official_email" name="official_email">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="mis_password" class="form-label">MIS Password</label>
                                <input type="password" class="form-control" placeholder="Enter Password if user need to be created" id="mis_password" name="mis_password" min="8">
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="user_modules" class="form-label">User Modules</label>
                                <select class="select2 mb-3" name="user_modules[]" id="user_modules" aria-label="Select User Modules" multiple>
                                    @foreach ($modules as $module)
                                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->

                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-label waves-effect waves-light"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save Employee</button>
            </div>
        </div>
    </div>

</form>

@endsection

@section('script')
<script>
    $(function(){
        $(".select2").select2();
        $(".flatpickr-date").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
        var cleave = new Cleave('#cnic', {
            delimiter: '-',
            blocks: [5, 7, 1],
            uppercase: true
        });
    })
</script>
@endsection
