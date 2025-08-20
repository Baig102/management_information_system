
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

<form action="<?php echo e(route('crm.close-traveling-bookings')); ?>" id="closeTravelingSearchForm" method="post" enctype="multipart/form-data">
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
                        <div class="col-lg-2">
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
                        <div class="col-lg-2 mb-3">
                            <label>Booking Number</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="booking_number"
                                    value="<?php echo e($searchParams['booking_number'] ?? ''); ?>"
                                    placeholder="Enter Booking Number" class="form-control" id="booking_number"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6 mb-3">
                            <label>Booking From Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="from_date"
                                    value="<?php echo e($searchParams['from_date'] ?? date('Y-m-d')); ?>" placeholder="DD-MM-YYYY"
                                    class="form-control flatpickr-date" data-provider="flatpickr"
                                    id="from_date" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-3">
                            <label>Booking To Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="to_date"
                                    value="<?php echo e($searchParams['to_date'] ?? \Carbon\Carbon::now()->addDays(30)->format('Y-m-d')); ?>"
                                    placeholder="DD-MM-YYYY" class="form-control flatpickr-date"
                                    data-provider="flatpickr" id="to_date" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <label for="ticket_status" class="form-label">Booking Status</label>
                            <select class="select2 form-control-sm" id="ticket_status" name="ticket_status"
                                data-placeholder="Select Booking status">
                                <option></option>
                                <?php $__currentLoopData = $ticket_status->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tstatus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tstatus->detail_number); ?>"><?php echo e($tstatus->details); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <label for="agent" class="form-label">Select Agent</label>
                            <select class="select2 form-control-sm" id="agent" name="created_by" data-placeholder="Select Agent">
                                <option></option>
                                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($agent->id); ?>" <?php echo e(isset($searchParams['created_by']) && $searchParams['created_by'] == $agent->id ? 'selected' : ((auth()->user()->role > 2 && auth()->user()->id == $agent->id) ? 'selected' : '')); ?>>
                                        <?php echo e($agent->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="<?php echo e(route('crm.close-traveling-bookings')); ?>" class="btn btn-danger"> <i class="ri-restart-line me-1 align-bottom"></i> Reset </a>
                        <button type="submit" class="btn btn-primary"> <i class="ri-equalizer-fill me-1 align-bottom"></i> Filter </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php if(isset($sortedBookings)): ?>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Close Traveling Booking List</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="alternative-pagination" class="alternative-pagination  table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12" data-ordering="false">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Booking Details</th>
                                    <th scope="col">Passenger Details</th>
                                    <th scope="col">Flight Details</th>
                                    <th scope="col">Payment Details</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $sortedBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $flight = $booking->flights->first();
                                        $passenger = $booking->passengers->first();

                                        $firstDepartureDate = Carbon\Carbon::parse($booking->flights->first()->departure_date ?? null);
                                        $rowClass = '';
                                        if ($firstDepartureDate->isToday()) {
                                            $rowClass = 'table-danger';
                                        } elseif ($firstDepartureDate->isTomorrow()) {
                                            $rowClass = 'table-warning';
                                        } else {
                                            $rowClass = 'table-success';
                                        }
                                    ?>
                                    <tr class="<?php echo e($rowClass); ?>">
                                        <td>
                                            <a href="javascript:void(0)" onclick="view_booking(<?php echo e($booking->id); ?>)" class="fw-semibold me-2"><i class="ri-coupon-line align-middle text-muted fs-12 me-2"></i>Booking #: <?php echo e($booking->booking_prefix . " " . $booking->booking_number); ?>

                                            </a>
                                            <p class="mb-0"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Company:</span> <?php echo e($booking->company->name); ?></p>
                                            <p class="mb-0 text-success"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Bookind Date:</span> <?php echo e(date("M-d-Y", strtotime($booking->booking_date))); ?></p>
                                            <p class="mb-0 text-secondary"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Booking Status:</span> <?php echo e($booking->stausDetails(1, 'ticket_status')->first()->details); ?></p>
                                            <hr>
                                            <p class="mb-0"><i class="ri-user-follow-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Created By:</span> <?php echo e(userDetails($booking->created_by)->name); ?> </p>
                                            <p class="mb-0"><i class="ri-calendar-2-line align-middle fs-12 me-2"></i><span class="fw-semibold">Created On:</span> <?php echo e(date('M-d-Y', strtotime($booking->created_at))); ?></p>
                                            <?php if($booking->updated_by != null): ?>
                                                <p class="mb-0 text-danger"><i class="ri-shield-user-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Updated By:</span> <?php echo e(userDetails($booking->updated_by)->name); ?> </p>
                                                <p class="mb-0 text-danger"><i class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Updated On:</span> <?php echo e(date('M-d-Y', strtotime($booking->updated_at))); ?></p>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="fs-12 mb-1">
                                                            <a href="javascript:void(0)" class="link-primary">
                                                                <?php echo e($passenger->title . " " . $passenger->first_name . " " . $passenger->middle_name . " " . $passenger->last_name); ?>

                                                            </a>
                                                        </h5>
                                                        <p class="text-muted mb-0"><a href="tel:<?php echo e($passenger->mobile_number); ?>" target="__blank" class="text-muted"><span class="fw-medium"><i class="ri-phone-line me-2 align-middle text-muted fs-16"></i><?php echo e($passenger->mobile_number); ?></span></a></p>
                                                        <p class="text-muted mb-0"><a href="mailto:<?php echo e($passenger->email); ?>" class="text-muted" target="__blank"><span class="fw-medium"><i class="ri-mail-line me-2 align-middle text-muted fs-16"></i><?php echo e($passenger->email); ?></span></a></p>
                                                    </div>
                                                </div>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if(empty($flight)): ?>
                                            <span class="ustify-content-center text-center text-danger">No Record Found</span>
                                            <?php else: ?>
                                            
                                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                                <div class="card-body">
                                                    <div class="ribbon ribbon-info ribbon-shape">
                                                        <?php echo e($booking->trip_type == 1 ? "One Way" : "Return"); ?>

                                                        <i class="ri-arrow-right-line align-middle"></i>
                                                        <?php echo e($booking->flight_type == 1 ? "Direct" : "In-Direct"); ?>

                                                        <i class="ri-arrow-left-right-line align-middle"></i>
                                                        Ticket: <?php echo e($booking->ticket_status == 1 ? "Pending" : "Generated"); ?>


                                                    </div>
                                                    <div class="row mt-4">
                                                        <div class="col-lg-5">
                                                            <h6 class="mb-2 text-primary">Departure Details</h6>
                                                            <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-takeoff-line"></i> <?php echo e($flight->departure_airport); ?></p>
                                                            <p class="mb-0"><i class=" ri-send-plane-fill"></i> <?php echo e($flight->air_line_name); ?></p>
                                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i> <?php echo e($flight->departure_date); ?> | <i class="ri-calendar-check-line"></i> <?php echo e($flight->departure_time); ?></p>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <spain class="text-danger display-6"><i class="ri-plane-line"></i></spain>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <h6 class="mb-2 text-warning">Arrival Details</h6>
                                                            <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-land-line"></i> <?php echo e($flight->arrival_airport); ?></p>
                                                            <p class="mb-0"><i class="ri-send-plane-fill"></i> <?php echo e($flight->air_line_name); ?></p>
                                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i> <?php echo e($flight->arrival_date); ?> | <i class="ri-calendar-check-line"></i> <?php echo e($flight->arrival_time); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-primary">
                                                    <div class="row">
                                                        <div class="col-lg-6 text-white">GRD PNR: <?php echo e($flight->gds_no); ?></div>
                                                        <div class="col-lg-6 text-white">Ticket Deadline:</span> <?php echo e(($booking->ticket_deadline != null) ? date("M-d-Y", strtotime($booking->ticket_deadline)) : 'null'); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <p class="fs-14"><b>Comments:</b> <?php echo e($booking->internalComments->first()?->comments ?? '-'); ?></p>
                                        </td>
                                        <td>
                                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                                <div class="card-body">
                                                    <ul class="list-group list-group-flush border-dashed mb-0 mt-0 pt-0">
                                                        <li class="list-group-item px-0">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 avatar-xs">
                                                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                                                        <img src="<?php echo e(URL::asset('build/images/svg/crypto-icons/gbp.svg')); ?>"
                                                                            class="img-fluid" alt="">
                                                                    </span>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <h6 class="mb-1">Amount Details</h6>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Sales Cost </p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Net Cost </p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Margin </p>
                                                                </div>
                                                                <div class="flex-shrink-0 text-end">
                                                                    <h6 class="mb-1">-</h6>
                                                                    <p class="fs-12 mb-0"><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->total_sales_cost, 2)); ?></p>
                                                                    <p class="fs-12 mb-0"><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->total_net_cost, 2)); ?></p>
                                                                    <p class="fs-12 mb-0"><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->margin, 2)); ?></p>
                                                                </div>
                                                            </div>
                                                        </li><!-- end -->
                                                        <li class="list-group-item px-0">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 avatar-xs">
                                                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                                                        <img src="<?php echo e(URL::asset('build/images/svg/crypto-icons/bix.svg')); ?>"
                                                                            class="img-fluid" alt="">
                                                                    </span>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <h6 class="mb-1">Deposit Details</h6>
                                                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Deposit Amount</p>
                                                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Last Deposit Date</p>
                                                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Balance Amount</p>
                                                                </div>
                                                                <div class="flex-shrink-0 text-end">
                                                                    <h6 class="mb-1">

                                                                    <?php if($booking->payment_status == 1): ?>
                                                                        <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Pending </span>
                                                                    <?php elseif($booking->payment_status == 2): ?>
                                                                        <span class="text-success"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Paid </span>
                                                                    <?php elseif($booking->payment_status == 3): ?>
                                                                        <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Balance Pending </span>
                                                                    <?php elseif($booking->payment_status == 4): ?>
                                                                        <span class="text-warning"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Partially Refunded </span>
                                                                    <?php elseif($booking->payment_status == 5): ?>
                                                                        <span class="text-warning"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Refunded </span>
                                                                    <?php endif; ?>
                                                                    </h6>
                                                                    <p class="fs-12 mb-0"><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->deposite_amount,2)); ?></p>
                                                                    <p class="fs-12 mb-0"><?php echo e(date('M-d-Y', strtotime($booking->deposit_date))); ?></p>
                                                                    <p class="fs-12 mb-0"><?php echo e($booking->currency); ?> <?php echo e($booking->balance_amount); ?></p>
                                                                </div>
                                                            </div>
                                                        </li><!-- end -->
                                                    </ul><!-- end -->
                                                    <div class="row d-none">
                                                        <div class="col-lg-6 border-end border-2">
                                                            <h6 class="mb-2 text-primary">Amount Details</h6>
                                                            <p class="mb-0">Sales Cost: <?php echo e($booking->currency); ?> <?php echo e(number_format($booking->total_sales_cost, 2)); ?></p>
                                                            <p class="mb-0">Net Cost: <?php echo e($booking->currency); ?> <?php echo e(number_format($booking->total_net_cost, 2)); ?></p>
                                                            <p class="mb-0">Margin: <?php echo e($booking->currency); ?> <?php echo e(number_format($booking->margin, 2)); ?></p>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <h6 class="mb-2 text-warning">Deposit Details</h6>
                                                            <p class="mb-0">Deposite Amount: <?php echo e($booking->currency); ?> <?php echo e(number_format($booking->deposite_amount,2)); ?></p>
                                                            <p class="mb-0">Last Deposite Date: <?php echo e(date('M-d-Y', strtotime($booking->deposit_date))); ?></p>
                                                            <p class="mb-0">Balance Amount <?php echo e($booking->currency); ?> <?php echo e($booking->balance_amount); ?></p>
                                                            <p class="mb-0">Payment:
                                                                <?php if($booking->payment_status == 1): ?>
                                                                    <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Pending </span>
                                                                <?php else: ?>
                                                                    <span class="text-success"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Paid </span>
                                                                <?php endif; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-equalizer-fill"></i> </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li class=""><button type="button" class="dropdown-item text-primary" onclick="view_booking(<?php echo e($booking->id); ?>)"><i class="ri-eye-fill align-bottom me-2"></i> View</button>
                                                    </li>
                                                    
                                                    </li>
                                                    <li><a class="dropdown-item edit-list text-dark" href="<?php echo e(route('crm.view-booking-invoice', $booking->id)); ?>" target="__blank"><i class=" ri-printer-line align-bottom me-2 "></i> Print</a>
                                                    <li><a class="dropdown-item edit-list text-success" href="#"><i class=" ri-mail-send-line align-bottom me-2 "></i> Email</a>
                                                    <li><a class="dropdown-item edit-list text-secondary" href="<?php echo e(route('crm.generate-booking-invoice', $booking->id)); ?>" target="__blank"><i class="ri-file-pdf-line align-bottom me-2 "></i> Download PDF</a>
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="17" class="text-center"> No Record Found!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- end table -->
                        
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
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

        // Calculate one month prior
        var oneMonthPrior = new Date();
        oneMonthPrior.setMonth(today.getMonth() - 1);

        // Initialize Flatpickr for the start date input
        var startDatePicker = flatpickr("#booking_from_date", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            defaultDate: oneMonthPrior,
            onChange: function(selectedDates, dateStr, instance) {
                // Set the selected date of the start date input as the min date for the end date input
                endDatePicker.set('minDate', selectedDates[0]);
            }
        });

        // Initialize Flatpickr for the end date input
        var endDatePicker = flatpickr("#booking_to_date", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                // Set the selected date of the end date input as the max date for the start date input
                startDatePicker.set('maxDate', selectedDates[0]);
            }
        });

        //$('.select2').destroy();
        initializeSelect2WithAjax('.airports', '<?php echo e(route('crm.get-airports')); ?>', 'Search for airports');
        initializeSelect2WithAjax('#airline', '<?php echo e(route('crm.get-airlines')); ?>', 'Search for airlines');
    })

    $(document).ready(function() {
        // Handle the export button click event
        $('#exportToExcelBtn').on('click', function() {
            // Serialize the entire form to capture all filter fields
            var formData = $('#closeTravelingSearchForm').serialize(); // Assuming your form has an ID "bookingSearchForm"

            // Send AJAX request to export filtered data to Excel
            $.ajax({
                url: "<?php echo e(route('crm.export-closed-traveling-bookings-to-excel')); ?>",
                method: 'POST',
                data: formData, // Pass the serialized form data
                success: function(response) {
                    //console.log(response);
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

<?php echo $__env->make('layouts.master-crm', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/reports/close-traveling-bookings.blade.php ENDPATH**/ ?>