<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.crm'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Dashboards
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Inquiry
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <?php $__env->startSection('css'); ?>
    <style>
        .tab-pane.fade {
            transition: all 0.2s;
            transform: translateY(1rem);
        }
        .tab-pane.fade.show {
            transform: translateY(0rem);
        }
    </style>
    <?php $__env->stopSection(); ?>
    <form action="<?php echo e(route('crm.save-inquiry')); ?>" method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <p class="<?php echo \Illuminate\Support\Arr::toCssClasses(['text-danger']); ?>">* Fields Are Required</p>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Inquiry Information</h4>
                        <div class="flex-shrink-0">
                            <a href="<?php echo e(route('crm.inquiry-list')); ?>" class="btn btn-soft-info btn-sm float-end btn-label"><i class="ri-database-fill label-icon align-middle fs-16 me-2"></i> Inquiries List </a>
                        </div>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <label for="company" class="form-label">Select Company<span class="text-danger"> *</span></label>
                                <select class="select2 form-control-sm" id="company" name="company" data-placeholder="Select Company" required>
                                    <option></option>
                                    <?php $__currentLoopData = $assignedCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assiCompany): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($assiCompany->id.'__'.$assiCompany->name); ?>"><?php echo e($assiCompany->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Inquiry Date & Time</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                    <input type="text" name="inquiry_date_time" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date-time" data-provider="flatpickr" id="inquiry_date_time" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-shield-user-line fs-5"></i></span>
                                    <input type="text" class="form-control full_name" id="full_name" name="full_name" value="" placeholder="Enter full name" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Email</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-mail-line fs-5"></i></span>
                                    <input type="email" class="form-control email" id="email" name="email" value="" placeholder="Enter email" step="0.01" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Phone #</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-phone-line fs-5"></i></span>
                                    <input type="text" class="form-control phone_number" id="phone_number" name="phone_number" value="" placeholder="Enter phone number" required autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Departure From</label>
                                <div class="input-group">
                                    <select class="select2 departure_from" id="departure_from" name="departure_from"></select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Destination</label>
                                <div class="input-group">
                                    <select class="select2 destination" id="destination" name="destination"></select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Departure Date</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                    <input type="text" name="departure_date" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="departure_date" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Return Date</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                    <input type="text" name="return_date" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="return_date" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Best Time To Call</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-timer-flash-line fs-5"></i></span>
                                    <input type="text" class="form-control best_time_to_call" id="best_time_to_call" name="best_time_to_call" value="Any Time" placeholder="Best Time To Call" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Source</label>
                                <select name="source" class="form-select mb-3" aria-label="Source">
                                    <option value="Facebook">Facebook</option>
                                    <option value="Website">Website</option>
                                    <option value="Via Phone Call" selected>Via Phone Call</option>
                                    <option value="Live Chat">Live Chat</option>
                                    <option value="Referral Client">Referral Client</option>
                                    <option value="Existing Client">Existing Client</option>
                                    <option value="From Messenger">From Messenger</option>
                                    <option value="From Social Media Comments">From Social Media Comments</option>
                                    <option value="Walk In Office">Walk In Office</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="text-end">
                <button type="submit" class="btn btn-primary btn-label waves-effect waves-light"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save Inquiry</button>
            </div>
            </div>
        </div>

    </form>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('filters-offcanvas'); ?>
    <!-- Filters -->
    

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(function() {
            $('.select2').select2();
            $(".flatpickr-date").flatpickr({
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            // Initialize Select2 when the modal is shown

        initializeSelect2WithAjax('#departure_from', '<?php echo e(route('crm.get-airports')); ?>', 'Search for departure from');
        initializeSelect2WithAjax('#destination', '<?php echo e(route('crm.get-airports')); ?>', 'Search for destination');

        })


        $(".flatpickr-date-time").flatpickr({
            altInput: true,
            //altFormat: "F j, Y",
            //dateFormat: "Y-m-d",
            dateFormat: "Y-m-d H:i:s",
            //minDate: "today",
            maxDate: "today",
            //defaultDate: "today",
            enableTime: true,
            time_24hr: true,
        });

        $(".flatpickr-date").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            //minDate: "today",
            //defaultDate: "today",
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-crm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/inquiry/create.blade.php ENDPATH**/ ?>