
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.crm'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Dashboards
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Booking
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

        .select2.selection {}
    </style>
<?php $__env->stopSection(); ?>
<script>
    // Define the base URL for your view booking route
    const viewBookingUrl = '<?php echo e(route('crm.view-booking', ['id' => 'booking_id'])); ?>';
</script>

<form action="<?php echo e(route('crm.daily-sales-report')); ?>" id="dailySalesReport" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Booking Payments Search</h4>
                    <?php if(Auth::user()->role <=3): ?>
                        <!-- Your Export to Excel button -->
                        <div class="flex-shrink-0">
                            <button type="button" id="exportToExcelBtn" class="btn btn-soft-info btn-sm material-shadow-none">
                                <i class="ri-file-list-3-line align-middle"></i> Export to Excel
                            </button>
                        </div>
                    <?php endif; ?>
                </div><!-- end card header -->
                <div class="card-body bg-light">

                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="company" class="form-label">Select Company</label>
                            <select class="select2 form-control-sm" id="company" name="company_id"
                                data-placeholder="Select Company">
                                <option></option>
                                <?php $__currentLoopData = $assignedCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assiCompany): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($assiCompany->id); ?>"
                                        <?php echo e(isset($filters['company_id']) && $filters['company_id'] == $assiCompany->id ? 'selected' : ''); ?>>
                                        <?php echo e($assiCompany->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3 d-none">
                            <label>Booking Number</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="booking_number"
                                    value="<?php echo e($filters['booking_number'] ?? ''); ?>"
                                    placeholder="Enter Booking Number" class="form-control" id="booking_number"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>Payment From Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="from_date"
                                    value="<?php echo e($filters['from_date'] ?? date('Y-m-d')); ?>" placeholder="DD-MM-YYYY"
                                    class="form-control flatpickr-date" data-provider="flatpickr"
                                    id="from_date" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>Payment To Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="to_date"
                                    value="<?php echo e($filters['to_date'] ?? date('Y-m-d')); ?>"
                                    placeholder="DD-MM-YYYY" class="form-control flatpickr-date"
                                    data-provider="flatpickr" id="to_date" autocomplete="off">
                            </div>
                        </div>

                    </div>

                </div>

                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="<?php echo e(route('crm.daily-sales-report')); ?>" class="btn btn-danger"> <i class="ri-restart-line me-1 align-bottom"></i> Reset </a>
                        <button type="submit" class="btn btn-primary"> <i class="ri-equalizer-fill me-1 align-bottom"></i> Filter </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> New Sales</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo e($total_booking); ?>"><?php echo e($total_booking); ?></span></h4>
                            
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success rounded fs-3">
                                <i class="bx bx-pound text-success"></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Sales Amount</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-danger fs-14 mb-0">
                                
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">£ <span class="counter-value" data-target=""></span></h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded fs-3">
                                <i class="bx bx-shopping-bag text-info"></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Received Payments</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">£ <span class="counter-value" data-target=""></span></h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning rounded fs-3">
                                <i class="bx bx-user-circle text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Balance</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-muted fs-14 mb-0">
                                
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">£ <span class="counter-value" data-target=""></span></h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                <i class="bx bx-wallet text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div> <!-- end row-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Daily Sales Report</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    
                    <div class="table-responsive">
                        
                            <table id="alternative-pagination" class="alternative-pagination table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Sr #</th>
                                    
                                    <th scope="col">Booking Date</th>
                                    <th scope="col">Invoice #</th>
                                    
                                    <th scope="col">Booking Status</th>
                                    <th scope="col">First Head</th>
                                    <th scope="col">Narration</th>
                                    <th scope="col">Against Head</th>
                                    <th scope="col">Amount</th>

                                    <th scope="col">Funds Category</th>
                                    <th scope="col">Agent</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                    <?php
                                        $passenger = $booking->passengers->first();
                                    ?>

                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td>
                                            
                                            <?php echo e(date('d-m-Y', strtotime($booking->created_at))); ?>

                                        </td>
                                        <td><?php echo e($booking->booking_prefix.$booking->booking_number); ?></td>
                                        
                                        <td><?php echo e($booking->stausDetails(1, 'ticket_status')->first()->details); ?></td>
                                        <td>
                                            <?php if(!$booking->payments->isEmpty()): ?>
                                                <?php if($booking->payments[0]->payment_method == 'Bank Transfer'): ?>
                                                    <?php echo e($booking->payments[0]->bank_name); ?>

                                                <?php elseif($booking->payments[0]->payment_method == 'Credit Debit Card'): ?>
                                                    <?php echo e($booking->payments[0]->card_type_type); ?>

                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e('From'); ?></td>
                                        <td><?php echo e($booking->booking_prefix.$booking->booking_number.' '. $passenger->title . " " . $passenger->first_name . " " . $passenger->middle_name . " " . $passenger->last_name); ?></td>
                                        <td>
                                            
                                            £ <?php echo e($booking->total_sales_cost); ?>

                                        </td>

                                        <td>
                                            <?php if(!$booking->payments->isEmpty()): ?>
                                                <?php echo e($booking->payments[0]->payment_method); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(userDetails($booking->created_by)->name); ?></td>
                                    </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="10" class="text-center"> No Record Found!</td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                        <!-- end table -->
                        
                        <!-- Pagination Links -->
                        
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    $(function() {
        $('select.select2').select2();
        $(".flatpickr-date").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        // Get today's date
        var today = new Date();
        // Set oneMonthPrior to the first day of the current month
        var oneMonthPrior = new Date(today.getFullYear(), today.getMonth(), 1);

        // Initialize Flatpickr for the start date input
        var startDatePicker = flatpickr("#payment_from_date", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            defaultDate: today,
            onChange: function(selectedDates, dateStr, instance) {
                // Set the selected date of the start date input as the min date for the end date input
                endDatePicker.set('minDate', selectedDates[0]);
            }
        });

        // Initialize Flatpickr for the end date input
        var endDatePicker = flatpickr("#payment_to_date", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                // Set the selected date of the end date input as the max date for the start date input
                startDatePicker.set('maxDate', selectedDates[0]);
            }
        });

    });

    $(document).ready(function() {
        // Handle the export button click event
        $('#exportToExcelBtn').on('click', function() {
            // Serialize the entire form to capture all filter fields
            var formData = $('#dailySalesReport').serialize(); // Assuming your form has an ID "bookingSearchForm"

            // Send AJAX request to export filtered data to Excel
            $.ajax({
                url: "<?php echo e(route('crm.export-daily-sales-report-to-excel')); ?>",
                method: 'POST',
                data: formData, // Pass the serialized form data
                success: function(response) {
                    // console.log(response);
                    // If the request was successful, download the file
                    if (response.url) {
                        window.location.href = response.url;
                    } else {
                        alert('Failed to generate the Excel file.');
                    }
                },
                error: function(xhr) {
                    //console.error(xhr.responseText);
                    alert('An error occurred while exporting the data.');
                }
            });
        });
    });


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-crm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/reports/daily-sales-report.blade.php ENDPATH**/ ?>