
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

<form action="<?php echo e(route('crm.daily-issuance-report')); ?>" id="dailyIssuanceReport" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Booking Daily Issuance Search</h4>
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
                        <div class="col-lg-3 d-none">
                            <label for="company" class="form-label">Select Company</label>
                            <select class="select2 form-control-sm" id="company" name="company_id"
                                data-placeholder="Select Company">
                                <option></option>
                                <?php $__currentLoopData = $assignedCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assiCompany): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($assiCompany->id); ?>"
                                        <?php echo e(isset($searchParams['company_id']) && $searchParams['company_id'] == $assiCompany->id ? 'selected' : ''); ?>>
                                        <?php echo e($assiCompany->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3 d-none">
                            <label>Booking Number</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="booking_number"
                                    value="<?php echo e($searchParams['booking_number'] ?? ''); ?>"
                                    placeholder="Enter Booking Number" class="form-control" id="booking_number"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>From Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="from_date"
                                    value="<?php echo e($searchParams['from_date'] ?? date('Y-m-d')); ?>" placeholder="DD-MM-YYYY"
                                    class="form-control flatpickr-date" data-provider="flatpickr"
                                    id="from_date" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>To Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="to_date"
                                    value="<?php echo e($searchParams['to_date'] ?? date('Y-m-d')); ?>"
                                    placeholder="DD-MM-YYYY" class="form-control flatpickr-date"
                                    data-provider="flatpickr" id="to_date" autocomplete="off">
                            </div>
                        </div>

                    </div>

                </div>

                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="<?php echo e(route('crm.daily-issuance-report')); ?>" class="btn btn-danger"> <i class="ri-restart-line me-1 align-bottom"></i> Reset </a>
                        <button type="submit" class="btn btn-primary"> <i class="ri-equalizer-fill me-1 align-bottom"></i> Filter </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Daily Issuance Report</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    
                    <div class="table-responsive">
                        
                            <table id="alternative-pagination" class="alternative-pagination table nowrap align-middle table-hover table-bordered align-middle mb-0 fs-12" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">Booking Date</th>
                                    <th scope="col">Invoice Number</th>
                                    <th scope="col">Client/Passenger Name</th>
                                    <th scope="col">Agent Name</th>
                                    <th scope="col">Service Type</th>
                                    <th scope="col">Service Details</th>
                                    <th scope="col" class="d-none">Route or Location (Sector or City)</th>
                                    <th scope="col">Travel/Stay Date</th>
                                    <th scope="col">Class/Room/Category</th>
                                    <th scope="col">Reference Number</th>
                                    <th scope="col">GDS Ref No</th>
                                    <th scope="col">Supplier Ref No</th>
                                    <th scope="col">Vendor Name</th>
                                    <th scope="col">Issue Date</th>
                                    <th scope="col">Issued By</th>
                                    <th scope="col">Invoice Status</th>
                                    <th scope="col">Net Amount</th>
                                    <th scope="col">Actual Amount</th>
                                    <th scope="col">Aviation Fee</th>
                                    <th scope="col">Profit</th>
                                    <th scope="col">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    
                                    <?php
                                        $passenger = $record->booking->passengers->first();
                                        $passengerName = $passenger->title." ".$passenger->first_name." ".$passenger->middle_name." ".$passenger->last_name;

                                        $actual_net_cost = $record->actual_net_cost + ($record->aviation_fee ?? 0);

                                        $flightNetTotal = 0;
                                        if ($record->booking->prices) {
                                            $flightNetTotal = $record->booking->prices->where('pricing_type', 'bookingFlight')->sum('net_total');
                                        }
                                        // $flightNetTotal = $record->booking->prices ->where('pricing_type', 'bookingFlight') ->sum('net_total');

                                        // $displayedNetTotal = $flightNetTotal != null && $flightNetTotal != 0 ? $flightNetTotal : record->net_cost;
                                    ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($record->booking->booking_date); ?></td>
                                        <td><a href="javascript:void(0)"><?php echo e($record->booking->booking_prefix.' '.$record->booking->booking_number); ?></a></td>
                                        <td><?php echo e($passengerName); ?></td>
                                        <td><?php echo e(userDetails($record->created_by)->name); ?></td>
                                        <td><?php echo e($record->record_type); ?></td>
                                        <!--Service Details-->
                                        <td>
                                            <?php switch($record->record_type):
                                                case ('Transport'): ?>
                                                    <!-- Display Transport Specific Data -->
                                                    <span><?php echo e($record->car_type); ?></span>
                                                    <?php break; ?>

                                                <?php case ('Hotel'): ?>
                                                    <!-- Display Hotel Specific Data -->
                                                    <?php echo e($record->hotel_name); ?>

                                                    <?php break; ?>

                                                <?php case ('Flight'): ?>

                                                    
                                                    <span><?php echo e($record->flights->first()->air_line_name ?? 'N/A'); ?></span>
                                                    <?php break; ?>

                                                <?php case ('Visa'): ?>
                                                    <!-- Display Visa Specific Data -->
                                                    <span><?php echo e($record->visa_category); ?></span>
                                                    <?php break; ?>

                                                <?php default: ?>
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>Unknown Type</span>
                                            <?php endswitch; ?>
                                        </td>
                                        <!--Route or location-->
                                        <td class="d-none">
                                            <?php switch($record->record_type):
                                                case ('Transport'): ?>
                                                    <!-- Display Transport Specific Data -->
                                                    <span><?php echo e($record->location); ?></span>
                                                    <?php break; ?>

                                                <?php case ('Hotel'): ?>
                                                    <!-- Display Hotel Specific Data -->
                                                    <?php echo e($record->hotel_name); ?>

                                                    <?php break; ?>

                                                <?php case ('Flight'): ?>

                                                    
                                                    <?php $__currentLoopData = $record->flights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span>
                                                            <?php echo e($flight->departure_airport.' -> '.$flight->arrival_airport); ?>

                                                        </span>
                                                        <?php if(!$loop->last): ?>
                                                            <br>
                                                        <?php endif; ?>

                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                    <?php break; ?>

                                                <?php case ('Visa'): ?>
                                                    <!-- Display Visa Specific Data -->
                                                    <span><?php echo e($record->visa_country); ?></span>
                                                    <?php break; ?>

                                                <?php default: ?>
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>Unknown Type</span>
                                            <?php endswitch; ?>
                                        </td>
                                        <!--Travel / Stay date-->
                                        <td>
                                            <?php switch($record->record_type):
                                                case ('Transport'): ?>
                                                    <!-- Display Transport Specific Data -->
                                                    <span><?php echo e($record->transport_date); ?></span>
                                                    <?php break; ?>

                                                <?php case ('Hotel'): ?>
                                                    <!-- Display Hotel Specific Data -->
                                                    <?php echo e($record->check_in_date); ?>

                                                    <?php break; ?>

                                                <?php case ('Flight'): ?>

                                                    <span><?php echo e(optional($record->flights->first())->departure_date ?? 'N/A'); ?></span>

                                                    <?php break; ?>

                                                <?php case ('Visa'): ?>
                                                    <!-- Display Visa Specific Data -->
                                                    <span>-</span>
                                                    <?php break; ?>

                                                <?php default: ?>
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>Unknown Type</span>
                                            <?php endswitch; ?>
                                        </td>
                                        <!--Class/Room/Category-->
                                        <td>
                                            <?php switch($record->record_type):
                                                case ('Transport'): ?>
                                                    <!-- Display Transport Specific Data -->
                                                    <span><?php echo e($record->car_type); ?></span>
                                                    <?php break; ?>

                                                <?php case ('Hotel'): ?>
                                                    <!-- Display Hotel Specific Data -->
                                                    <?php echo e($record->room_type); ?>

                                                    <?php break; ?>

                                                <?php case ('Flight'): ?>

                                                    <span><?php echo e(optional($record->flights->first())->booking_class ?? 'N/A'); ?></span>

                                                    <?php break; ?>

                                                <?php case ('Visa'): ?>
                                                    <!-- Display Visa Specific Data -->
                                                    <span><?php echo e($record->remarks); ?></span>
                                                    <?php break; ?>

                                                <?php default: ?>
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>Unknown Type</span>
                                            <?php endswitch; ?>
                                        </td>
                                        <!-- Reference Number (Ticket/Confirmation/Visa No) -->
                                        <td>
                                            <?php switch($record->record_type):
                                                case ('Transport'): ?>
                                                    <!-- Display Transport Specific Data -->
                                                    <span><?php echo e('-'); ?></span>
                                                    <?php break; ?>

                                                <?php case ('Hotel'): ?>
                                                    <!-- Display Hotel Specific Data -->
                                                    <?php echo e($record->hotel_confirmation_number); ?>

                                                    <?php break; ?>

                                                <?php case ('Flight'): ?>

                                                    <span><?php echo e(optional($record->flights->first())->ticket_no ?? 'N/A'); ?></span>

                                                    <?php break; ?>

                                                <?php case ('Visa'): ?>
                                                    <!-- Display Visa Specific Data -->
                                                    <span><?php echo e('-'); ?></span>
                                                    <?php break; ?>

                                                <?php default: ?>
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>Unknown Type</span>
                                            <?php endswitch; ?>
                                        </td>
                                        <!--  GDS Ref No (or Booking Ref) -->
                                        <td>
                                            <?php switch($record->record_type):

                                                case ('Flight'): ?>

                                                    <span><?php echo e(optional($record->flights->first())->gds_no ?? 'N/A'); ?></span>

                                                    <?php break; ?>

                                                <?php default: ?>
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>N/A</span>
                                            <?php endswitch; ?>
                                        </td>
                                        <!-- Supplier Ref No/-->
                                        <td>-</td>
                                        <!-- Vendor Name -->
                                        <td> <?php echo e($record->supplier); ?> </td>
                                        <td><?php echo e(date('d-m-Y', strtotime($record->actual_net_on))); ?></td>
                                        <td><?php echo e(($record->actual_net_by != null) ? userDetails($record->actual_net_by)->name : '-'); ?></td>
                                        <td> <?php echo e($record->booking->stausDetails(1, 'ticket_status')->first()->details); ?> </td>

                                        <td>
                                            <?php switch($record->record_type):

                                                case ('Flight'): ?>

                                                    <span><?php echo e($flightNetTotal); ?></span>

                                                    <?php break; ?>

                                                <?php default: ?>
                                                    <!-- Default case if type doesn't match any -->
                                                    <span><?php echo e(number_format($record->net_cost, 2)); ?></span>
                                            <?php endswitch; ?>
                                        </td>
                                        
                                        
                                        <td><?php echo e(number_format($record->actual_net_cost, 2)); ?></td>
                                        
                                        <td><?php echo e(number_format($record->aviation_fee ?? 0, 2)); ?></td>
                                        <td>
                                            <?php switch($record->record_type):

                                                case ('Flight'): ?>

                                                    <span><?php echo e(number_format(($flightNetTotal - $record->actual_net_cost), 2)); ?></span>

                                                    <?php break; ?>

                                                <?php default: ?>
                                                    <!-- Default case if type doesn't match any -->
                                                    <span><?php echo e(number_format(($record->net_cost - $record->actual_net_cost), 2)); ?></span>
                                            <?php endswitch; ?>
                                        </td>
                                        
                                        <td><?php echo e($record->comments); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="21" class="text-center">No records found</td>
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
        var startDatePicker = flatpickr("#from_date", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            defaultDate: today,
            onChange: function(selectedDates, dateStr, instance) {
                // Set the selected date of the start date input as the min date for the end date input
                endDatePicker.set('minDate', selectedDates[0]);
            }
        });

        // Initialize Flatpickr for the end date input
        var endDatePicker = flatpickr("#to_date", {
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
            var formData = $('#dailyIssuanceReport').serialize(); // Assuming your form has an ID "bookingSearchForm"

            // Send AJAX request to export filtered data to Excel
            $.ajax({
                url: "<?php echo e(route('crm.export-daily-issuance-report-to-excel')); ?>",
                method: 'POST',
                data: formData, // Pass the serialized form data
                success: function(response) {
                    console.log(response);
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

<?php echo $__env->make('layouts.master-crm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/reports/daily-issuance-report.blade.php ENDPATH**/ ?>