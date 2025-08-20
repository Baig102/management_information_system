<form action="<?php echo e(route('hrm.employee-update')); ?>" method="post" id="updateEmployee" enctype="multipart/form-data">
    
    <?php echo csrf_field(); ?>
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
                                    <input type="number" class="form-control" value="<?php echo e($data->code); ?>" placeholder="Enter Employee Code" id="employee_code" name="code" readonly>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <select class="form-control" id="title" name="title">
                                        <option value="Mr." <?php echo e($data->title == 'Mr.' ? 'selected' : ''); ?>>Mr.
                                        </option>
                                        <option value="Ms." <?php echo e($data->title == 'Ms.' ? 'selected' : ''); ?>>Ms.
                                        </option>
                                        <option value="Mrs." <?php echo e($data->title == 'Mrs.' ? 'selected' : ''); ?>>Mrs.
                                        </option>
                                        <option value="Miss." <?php echo e($data->title == 'Miss.' ? 'selected' : ''); ?>>Miss.
                                        </option>
                                        <option value="Mstr." <?php echo e($data->title == 'Mstr.' ? 'selected' : ''); ?>>Mstr.
                                        </option>
                                    </select>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="<?php echo e($data->name); ?>" placeholder="Enter your full name" id="full_name" name="name">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="guardian_name" class="form-label">Guardian Name</label>
                                    <input type="text" class="form-control" value="<?php echo e($data->guardian_name); ?>" placeholder="Enter your Guardian Name" id="guardian_name" name="guardian_name">
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
                                    <input type="text" class="form-control" value="<?php echo e($data->personal_email); ?>" placeholder="Enter your personal email" id="personal_email" name="personal_email">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="personal_phone" class="form-label">Personal Phone</label>
                                    <input type="text" class="form-control" value="<?php echo e($data->personal_phone); ?>" placeholder="Enter your personal phone" id="personal_phone" name="personal_phone">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="cnic" class="form-label">CNIC</label>
                                    <input type="text" class="form-control" value="<?php echo e($data->cnic); ?>" placeholder="Enter your CNIC" id="cnic" name="cnic">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="select2 form-control" id="gender" name="gender">
                                        <option value="Male" <?php echo e($data->gender == 'Male' ? 'selected' : ''); ?>>Male</option>
                                        <option value="Female" <?php echo e(($data->gender = 'Female') ? 'selected' : ''); ?>>Female</option>
                                    </select>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <input type="text" class="form-control flatpickr-date" value="<?php echo e($data->dob); ?>" placeholder="Enter your DOB" id="dob" name="dob">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="marital_status" class="form-label">Marital Status</label>
                                    <select class="select2 form-control" id="marital_status" name="marital_status">
                                        <option value="0" <?php echo e($data->martial_status == '0' ? 'selected' : ''); ?>>
                                            Single</option>
                                        <option value="1" <?php echo e($data->martial_status == '1' ? 'selected' : ''); ?>>
                                            Married</option>
                                    </select>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="education" class="form-label">Education</label>
                                    <input type="text" class="form-control" value="<?php echo e($data->education); ?>" placeholder="Enter education" id="education" name="education">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="experience" class="form-label">Experience</label>
                                    <input type="text" class="form-control" value="<?php echo e($data->experience); ?>" placeholder="Enter experience" id="experience" name="experience">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="religion" class="form-label">Religion</label>
                                    <select class="select2 form-control mb-3" name="religion" id="religion" aria-label="Select Religion">
                                        <option value="Christianity" <?php echo e($data->religion == 'Christianity' ? 'selected' : ''); ?>>Christianity
                                        </option>
                                        <option value="Hinduism" <?php echo e($data->religion == 'Hinduism' ? 'selected' : ''); ?>>Hinduism</option>
                                        <option value="Islam" <?php echo e($data->religion == 'Islam' ? 'selected' : ''); ?>>
                                            Islam</option>
                                        <option value="Other" <?php echo e($data->religion == 'Other' ? 'selected' : ''); ?>>
                                            Other</option>
                                    </select>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="blood_group" class="form-label">Blood Group</label>
                                    <select class="select2 form-control mb-3" name="blood_group" id="blood_group" aria-label="Select Blood Group">
                                        <option></option>
                                        <option value="A+" <?php echo e($data->blood_group == 'A+' ? 'selected' : ''); ?>>A+
                                        </option>
                                        <option value="A-" <?php echo e($data->blood_group == 'A-' ? 'selected' : ''); ?>>A-
                                        </option>
                                        <option value="B+" <?php echo e($data->blood_group == 'B+' ? 'selected' : ''); ?>>B+
                                        </option>
                                        <option value="B-" <?php echo e($data->blood_group == 'B-' ? 'selected' : ''); ?>>B-
                                        </option>
                                        <option value="O+" <?php echo e($data->blood_group == 'O+' ? 'selected' : ''); ?>>O+
                                        </option>
                                        <option value="O-" <?php echo e($data->blood_group == 'O-' ? 'selected' : ''); ?>>O-
                                        </option>
                                        <option value="AB+" <?php echo e($data->blood_group == 'AB+' ? 'selected' : ''); ?>>
                                            AB+</option>
                                        <option value="AB-" <?php echo e($data->blood_group == 'AB-' ? 'selected' : ''); ?>>
                                            AB-</option>
                                    </select>
                                </div>
                            </div><!--end col-->
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" value="<?php echo e($data->city); ?>" placeholder="Enter city" id="city" name="city">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control" value="<?php echo e($data->state); ?>" placeholder="Enter state" id="state" name="state">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" value="<?php echo e($data->address); ?>" placeholder="Enter address" id="address" name="address">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="zip_code" class="form-label">Zip Code</label>
                                    <input type="number" class="form-control" value="<?php echo e($data->zip_code); ?>" placeholder="Enter zip code" id="zip_code" name="zip_code">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2 mb-3">
                                <label for="" class="form-label">Is Active</label>
                                <!-- Custom Switches -->
                                <div class="form-check form-switch form-switch-custom form-switch-primary">
                                    <input class="form-check-input" type="checkbox" name="is_active" role="switch" id="isActive" <?php echo e($data->is_active == '1' ? 'checked' : ''); ?>>
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
                                        <option value="Permanent" <?php echo e($data->employment_type == 'Permanent' ? 'selected' : ''); ?>>Permanent
                                        </option>
                                        <option value="Temp" <?php echo e($data->employment_type == 'Temp' ? 'selected' : ''); ?>>Temp</option>
                                    </select>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="designation" class="form-label">Designation</label>
                                    <select class="select2 form-control mb-3" name="designation" id="designation" aria-label="Select designation">
                                        <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($designation->detail_number . '__' . $designation->name); ?>" <?php echo e($data->designation == $designation->name ? 'selected' : ''); ?>>
                                            <?php echo e($designation->name); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="select2 form-control mb-3" name="department_id" id="department" aria-label="Select department">
                                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($department->id); ?>" <?php echo e($data->department_id == $department->id ? 'selected' : ''); ?>>
                                            <?php echo e($department->name); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="team_lead_id" class="form-label">Manager / Team Lead </label>
                                    <select class="select2 form-control mb-3" name="team_lead_id" id="team_lead_id" aria-label="Manager / Team Lead">
                                        <option></option>
                                        <?php $__currentLoopData = $teamLeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team_lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($team_lead->user->id); ?>" <?php echo e($data->team_lead_id == $team_lead->user->id ? 'selected' : ''); ?>><?php echo e($team_lead->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="joining_date" class="form-label">Joining Date</label>
                                    <input type="text" class="form-control flatpickr-date" placeholder="Enter Joining Date" id="joining_date" name="joining_date" value="<?php echo e($data->joining_date); ?>">
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="basic_salary" class="form-label">Basic Salary</label>
                                    <input type="number" class="form-control" placeholder="Enter Basic Salary" id="basic_salary" name="basic_salary" value="<?php echo e($data->basic_salary); ?>" step="0.01">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="companies" class="form-label">Company Names</label>
                                    <select class="select2 form-control" name="companies[]" id="companies" aria-label="Select Company Names" multiple>
                                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($company->id); ?>" <?php echo e(in_array($company->id, $assignedCompanyIds) ? 'selected' : ''); ?>><?php echo e($company->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="business_customers" class="form-label">Business Customers</label>
                                    <select class="select2 form-control" name="business_customers[]" id="business_customers" aria-label="Select Business Customers" multiple>
                                        <?php $__currentLoopData = $businessCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $businessCustomer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($businessCustomer->id); ?>" <?php echo e(in_array($businessCustomer->id, $assignedBusinessCustomerIds) ? 'selected' : ''); ?>><?php echo e($businessCustomer->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="official_phone" class="form-label">Official Phone Number</label>
                                    <input type="tel" class="form-control" placeholder="+(245) 451 45123" id="official_phone" value="<?php echo e($data->official_phone); ?>" name="official_phone">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="official_email" class="form-label">Official Email Address</label>
                                    <input type="email" class="form-control" placeholder="example@gamil.com" id="official_email" value="<?php echo e($data->official_email); ?>" name="official_email">
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
                                        <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($module->id); ?>" <?php echo e(in_array($module->id, $assignedModuleIds) ? 'selected' : ''); ?>><?php echo e($module->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

        <input type="hidden" name="employee_id" id="employee_id" value="<?php echo e($data->id); ?>">
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
                url: "<?php echo e(route('hrm.employee-update')); ?>",
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
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/HRM/employee/modals/edit.blade.php ENDPATH**/ ?>