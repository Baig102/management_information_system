<!-- resources/views/exports/bookings.blade.php -->

<table>
    <thead>
        <tr>
            <th><strong>Sr #</strong></th>
            <th><strong>Booking Date</strong></th>
            <th><strong>Booking #</strong></th>
            <th><strong>Passenger Name</strong></th>
            <th><strong>E-mail</strong></th>
            <th><strong>Agent</strong></th>
            <th><strong>Travel Date</strong></th>
            <th><strong>Departure ➝ Arrival</strong></th>
            <th><strong>Airline</strong></th>
            <th><strong>Status</strong></th>
            <th><strong>Sale</strong></th>
            <th><strong>Deposit</strong></th>
            <th><strong>Remaining</strong></th>
            <th><strong>Notes</strong></th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $passenger = $booking->passengers->first();
            $flight = $booking->flights->first();
        ?>
        <tr>
            <td><?php echo e($key + 1); ?></td>
            <td>
                <?php echo e(date('d-m-Y', strtotime($booking->created_at))); ?>

            </td>
            <td><?php echo e($booking->booking_prefix.$booking->booking_number); ?></td>
            <td><?php echo e($passenger->title . " " . $passenger->first_name . " " . $passenger->middle_name . " " . $passenger->last_name); ?></td>
            <td><?php echo e($passenger->email); ?></td>
            <td><?php echo e(userDetails($booking->created_by)->name); ?></td>
            <td><?php echo e($flight->departure_date); ?></td>
            <td><?php echo e($flight->departure_airport); ?> ➝ <?php echo e($flight->arrival_airport); ?></td>
            <td><?php echo e($flight->air_line_name); ?></td>
            <td><?php echo e($booking->stausDetails(1, 'ticket_status')->first()->details); ?></td>
            <td><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->total_sales_cost, 2)); ?></td>
            <td><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->deposite_amount,2)); ?></td>
            <td><?php echo e($booking->currency); ?> <?php echo e($booking->balance_amount); ?></td>
            <td><?php echo e($booking->internalComments->first()?->comments ?? '-'); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/exports/close-traveling-bookings.blade.php ENDPATH**/ ?>