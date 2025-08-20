<!-- resources/views/exports/bookings.blade.php -->

<table>
    <thead>
        <tr>
            <th><strong>Sr #</strong></th>
            <th><strong>Booking Date</strong></th>
            <th><strong>Invoice Number</strong></th>
            <th><strong>Client/Passenger Name</strong></th>
            <th><strong>Agent Name</strong></th>
            <th><strong>Service Type</strong></th>
            <th><strong>Service Details</strong></th>
            
            <th><strong>Travel/Stay Date</strong></th>
            <th><strong>Class/Room/Category</strong></th>
            <th><strong>Reference Number</strong></th>
            <th><strong>GDS Ref No</strong></th>
            <th><strong>Supplier Ref No</strong></th>
            <th><strong>Vendor Name</strong></th>
            <th><strong>Issue Date</strong></th>
            <th><strong>Issued By</strong></th>
            <th><strong>Invoice Status</strong></th>
            <th><strong>Net Amount</strong></th>
            <th><strong>Actual Amount</strong></th>
            <th><strong>Aviation Fee</strong></th>
            <th><strong>Profit</strong></th>
            <th><strong>Remarks</strong></th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <?php
                $passenger = $record->booking->passengers->first();
                $passengerName = $passenger->title." ".$passenger->first_name." ".$passenger->middle_name." ".$passenger->last_name;

                $actual_net_cost = $record->actual_net_cost + ($record->aviation_fee ?? 0);

                $flightNetTotal = 0;
                if ($record->booking->prices) {
                    $flightNetTotal = $record->booking->prices->where('pricing_type', 'bookingFlight')->sum('net_total');
                }
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
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/exports/daily-issuance-report.blade.php ENDPATH**/ ?>