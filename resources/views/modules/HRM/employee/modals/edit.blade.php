<form action="{{ route('hrm.employee-update') }}" method="post" id="updateEmployee" enctype="multipart/form-data">
    {{-- <form id="updateEmployee"> --}}
    @csrf
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Edit Employee Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Personal Details</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="employee_code" class="form-label">Employee Code</label>
                                    <input type="number" class="form-control" value="{{ $data->code }}" placeholder="Enter Employee Code" id="employee_code" name="code" readonly>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <select class="form-control" id="title" name="title">
                                        <option value="Mr." {{ $data->title == 'Mr.' ? 'selected' : '' }}>Mr.
                                        </option>
                                        <option value="Ms." {{ $data->title == 'Ms.' ? 'selected' : '' }}>Ms.
                                        </option>
                                        <option value="Mrs." {{ $data->title == 'Mrs.' ? 'selected' : '' }}>Mrs.
                                        </option>
                                        <option value="Miss." {{ $data->title == 'Miss.' ? 'selected' : '' }}>Miss.
                                        </option>
                                        <option value="Mstr." {{ $data->title == 'Mstr.' ? 'selected' : '' }}>Mstr.
                                        </option>
                                    </select>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="{{ $data->name }}" placeholder="Enter your full name" id="full_name" name="name">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="guardian_name" class="form-label">Guardian Name</label>
                                    <input type="text" class="form-control" value="{{ $data->guardian_name }}" placeholder="Enter your Guardian Name" id="guardian_name" name="guardian_name">
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
                                    <input type="text" class="form-control" value="{{ $data->personal_email }}" placeholder="Enter your personal email" id="personal_email" name="personal_email">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="personal_phone" class="form-label">Personal Phone</label>
                                    <input type="text" class="form-control" value="{{ $data->personal_phone }}" placeholder="Enter your personal phone" id="personal_phone" name="personal_phone">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="cnic" class="form-label">CNIC</label>
                                    <input type="text" class="form-control" value="{{ $data->cnic }}" placeholder="Enter your CNIC" id="cnic" name="cnic">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="select2 form-control" id="gender" name="gender">
                                        <option value="Male" {{ $data->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ ($data->gender = 'Female') ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <input type="text" class="form-control flatpickr-date" value="{{ $data->dob }}" placeholder="Enter your DOB" id="dob" name="dob">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="marital_status" class="form-label">Marital Status</label>
                                    <select class="select2 form-control" id="marital_status" name="marital_status">
                                        <option value="0" {{ $data->martial_status == '0' ? 'selected' : '' }}>
                                            Single</option>
                                        <option value="1" {{ $data->martial_status == '1' ? 'selected' : '' }}>
                                            Married</option>
                                    </select>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="education" class="form-label">Education</label>
                                    <input type="text" class="form-control" value="{{ $data->education }}" placeholder="Enter education" id="education" name="education">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="experience" class="form-label">Experience</label>
                                    <input type="text" class="form-control" value="{{ $data->experience }}" placeholder="Enter experience" id="experience" name="experience">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="religion" class="form-label">Religion</label>
                                    <select class="select2 form-control mb-3" name="religion" id="religion" aria-label="Select Religion">
                                        <option value="Christianity" {{ $data->religion == 'Christianity' ? 'selected' : '' }}>Christianity
                                        </option>
                                        <option value="Hinduism" {{ $data->religion == 'Hinduism' ? 'selected' : '' }}>Hinduism</option>
                                        <option value="Islam" {{ $data->religion == 'Islam' ? 'selected' : '' }}>
                                            Islam</option>
                                        <option value="Other" {{ $data->religion == 'Other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="blood_group" class="form-label">Blood Group</label>
                                    <select class="select2 form-control mb-3" name="blood_group" id="blood_group" aria-label="Select Blood Group">
                                        <option></option>
                                        <option value="A+" {{ $data->blood_group == 'A+' ? 'selected' : '' }}>A+
                                        </option>
                                        <option value="A-" {{ $data->blood_group == 'A-' ? 'selected' : '' }}>A-
                                        </option>
                                        <option value="B+" {{ $data->blood_group == 'B+' ? 'selected' : '' }}>B+
                                        </option>
                                        <option value="B-" {{ $data->blood_group == 'B-' ? 'selected' : '' }}>B-
                                        </option>
                                        <option value="O+" {{ $data->blood_group == 'O+' ? 'selected' : '' }}>O+
                                        </option>
                                        <option value="O-" {{ $data->blood_group == 'O-' ? 'selected' : '' }}>O-
                                        </option>
                                        <option value="AB+" {{ $data->blood_group == 'AB+' ? 'selected' : '' }}>
                                            AB+</option>
                                        <option value="AB-" {{ $data->blood_group == 'AB-' ? 'selected' : '' }}>
                                            AB-</option>
                                    </select>
                                </div>
                            </div><!--end col-->
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" value="{{ $data->city }}" placeholder="Enter city" id="city" name="city">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control" value="{{ $data->state }}" placeholder="Enter state" id="state" name="state">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" value="{{ $data->address }}" placeholder="Enter address" id="address" name="address">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="zip_code" class="form-label">Zip Code</label>
                                    <input type="number" class="form-control" value="{{ $data->zip_code }}" placeholder="Enter zip code" id="zip_code" name="zip_code">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2 mb-3">
                                <label for="" class="form-label">Is Active</label>
                                <!-- Custom Switches -->
                                <div class="form-check form-switch form-switch-custom form-switch-primary">
                                    <input class="form-check-input" type="checkbox" name="is_active" role="switch" id="isActive" {{ $data->is_active == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Yes</label>
                                </div>
                            </div>
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
                                    <select class="select2 form-control" id="employment_type" name="employment_type">
                                        <option value="Permanent" {{ $data->employment_type == 'Permanent' ? 'selected' : '' }}>Permanent
                                        </option>
                                        <option value="Temp" {{ $data->employment_type == 'Temp' ? 'selected' : '' }}>Temp</option>
                                    </select>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="designation" class="form-label">Designation</label>
                                    <select class="select2 form-control mb-3" name="designation" id="designation" aria-label="Select designation">
                                        @foreach ($designations as $designation)
                                        <option value="{{ $designation->detail_number . '__' . $designation->name }}" {{ $data->designation == $designation->name ? 'selected' : '' }}>
                                            {{ $designation->name }}
                                        </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="select2 form-control mb-3" name="department_id" id="department" aria-label="Select department">
                                        @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ $data->department_id == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="team_lead_id" class="form-label">Manager / Team Lead </label>
                                    <select class="select2 form-control mb-3" name="team_lead_id" id="team_lead_id" aria-label="Manager / Team Lead">
                                        <option></option>
                                        @foreach ($teamLeads as $team_lead)
                                        <option value="{{ $team_lead->user->id }}" {{ $data->team_lead_id == $team_lead->user->id ? 'selected' : '' }}>{{ $team_lead->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="joining_date" class="form-label">Joining Date</label>
                                    <input type="text" class="form-control flatpickr-date" placeholder="Enter Joining Date" id="joining_date" name="joining_date" value="{{ $data->joining_date }}">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="basic_salary" class="form-label">Basic Salary</label>
                                    <input type="number" class="form-control" placeholder="Enter Basic Salary" id="basic_salary" name="basic_salary" value="{{ $data->basic_salary }}" step="0.01">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="companies" class="form-label">Company Names</label>
                                    <select class="select2 form-control" name="companies[]" id="companies" aria-label="Select Company Names" multiple>
                                        @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" {{ in_array($company->id, $assignedCompanyIds) ? 'selected' : '' }}>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="business_customers" class="form-label">Business Customers</label>
                                    <select class="select2 form-control" name="business_customers[]" id="business_customers" aria-label="Select Business Customers" multiple>
                                        @foreach ($businessCustomers as $businessCustomer)
                                            <option value="{{ $businessCustomer->id }}" {{ in_array($businessCustomer->id, $assignedBusinessCustomerIds) ? 'selected' : '' }}>{{ $businessCustomer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="official_phone" class="form-label">Official Phone Number</label>
                                    <input type="tel" class="form-control" placeholder="+(245) 451 45123" id="official_phone" value="{{ $data->official_phone }}" name="official_phone">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="official_email" class="form-label">Official Email Address</label>
                                    <input type="email" class="form-control" placeholder="example@gamil.com" id="official_email" value="{{ $data->official_email }}" name="official_email">
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
                                    <select class="select2 form-control mb-3" name="user_modules[]" id="user_modules" aria-label="Select User Modules" multiple>
                                        @foreach ($modules as $module)
                                            <option value="{{ $module->id }}" {{ in_array($module->id, $assignedModuleIds) ? 'selected' : '' }}>{{ $module->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!--end col-->

                        </div><!--end row-->

                    </div>
                    <!-- end card body -->
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">

        <input type="hidden" name="employee_id" id="employee_id" value="{{ $data->id }}">
        <button type="submit" class="btn btn-primary btn-sm" id="updateEmployee"><i class="ri-save-3-line me-1 align-middle"></i>Update</button>

        <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    </div>
</form>


<script>
    $(".flatpickr-date").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        //minDate: "today",
        defaultDate: "today"
    });

    var cleave = new Cleave('#cc-number', {
        creditCard: true,
        onCreditCardTypeChanged: function(type) {
            // update UI ...
            //console.log(type);
            $('#card_type').val(type);
        }
    });

    var cleave = new Cleave('#cc-expiration', {
        date: true,
        delimiter: '/',
        datePattern: ['m', 'y']
    });

    $(document).ready(function() {

        ClassicEditor
            .create(document.querySelector('#comments'))
            .catch(error => {
                console.error(error);
            })

        // Listen for click event on radio buttons
        $('input[type="radio"]').on('click', function() {
            // Check if the clicked radio button is checked
            if ($(this).is(':checked')) {
                // Get the value of the clicked radio button
                var radioValue = $(this).val();
                if (radioValue == 'Credit Debit Card') {
                    var installment_amount = $('#installment_amount').val();
                    $('#cc-ccc').val((installment_amount * 0.03).toFixed(2));

                } else {
                    $('#credit_debit_card_transfer input').each(function() {
                        // Do something with each input field
                        $(this).val('');
                    });
                }
            }
        });

        // Validate form before submitting
        $('#updateEmployee').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            //var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var booking_id = $('[name="booking_id"]').val();

            event.preventDefault();
            /* $.ajax({
                type: 'POST',
                url: "{{ route('hrm.employee-update') }}",
                data: $(this).serialize(),
                success: function(response) {
                    //console.log(response);
                    if (response.code == 200) {
                        $('.modal.extraLargeModal').modal('toggle');
                        $('.modal.fullscreeexampleModal').modal('toggle');
                        view_booking(booking_id);
                    }

                    Swal.fire({
                        title: response.title,
                        icon: response.icon,
                        text: response.message,
                    });
                },
                error: function(xhr, status, error) {
                    //console.log(xhr, status, error);
                    // Show error message
                    //alert(xhr.responseJSON.message);

                    Swal.fire({
                        //title: response.title,
                        //icon: response.icon,
                        text: xhr.responseJSON.message,
                    });
                }
            });
            return; */
        });
    });
</script>
