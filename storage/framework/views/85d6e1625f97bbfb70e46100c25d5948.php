<!-- resources/views/exports/bookings.blade.php -->

<table>
    <thead>
        <tr>
            <th><strong>INVOICE #</strong></th>
            <th><strong>BOOKING DATE</strong></th>
            <th><strong>CLIENT NAME</strong></th>
            
            <th><strong>DEPARTURE AIRPORT</strong></th>
            <th><strong>DEPARTURE DATE</strong></th>
            <th><strong>ARRIVAL AIRPORT</strong></th>
            <th><strong>ARRIVAL DATE</strong></th>
            <th><strong>AGENT NAME</strong></th>
            <th><strong>BOOKING STATUS</strong></th>
            <th><strong>SALE COST</strong></th>
            <th><strong>NET COST</strong></th>
            <th><strong>DEPOSIT</strong></th>
            <th><strong>REMAINING AMOUNT</strong></th>
            <th><strong>PROFIT</strong></th>
            <th><strong>OTHER CHARGES</strong></th>
            <th><strong>TOTAL SALE COST</strong></th>
            <th><strong>REFUNDED AMOUNT</strong></th>
            <th><strong>PROFIT IN INVOICE</strong></th>
            <th><strong>INSTALLMENT AMOUNT</strong></th>
            <th><strong>DUE DATE</strong></th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $flight = $booking->flights->first();
            $passenger = $booking->passengers->first();
            $installmentPlan = $booking->installmentPlan
                ->where('is_received', 0)
                ->sortByDesc('due_date')
                ->first();

            $totalOtherCharges = $booking->otherCharges->isNotEmpty() ? $booking->otherCharges->sum('amount') : "0.00";
            $total_with_other_charges = $booking->total_sales_cost + $totalOtherCharges;
            $profit_in_invoice = ($total_with_other_charges-$booking->total_net_cost)-$booking->refunded_amount;

            $flightDetails = resolveFlightAirportsByTripType($booking->id);
        ?>
        <tr>
            <td><?php echo e($booking->booking_prefix.$booking->booking_number); ?></td>
            <td><?php echo e($booking->booking_date); ?></td>
            <td><?php echo e($passenger->title . " " . $passenger->first_name . " " . $passenger->middle_name . " " . $passenger->last_name); ?></td>
            
            <?php if(empty($flight)): ?>
                <td colspan="4">-</td>
            <?php else: ?>
            
            
            <td><?php echo e($flightDetails['arrival_airport']); ?></td>
            <td><?php echo e($flight->departure_date); ?></td>
            
            <td><?php echo e($flightDetails['arrival_airport']); ?></td>
            
            <td><?php echo e($flight->arrival_date); ?></td>
            <?php endif; ?>
            <td><?php echo e(userDetails($booking->created_by)->name); ?></td>
            <td><?php echo e($booking->stausDetails(1, 'ticket_status')->first()->details); ?></td>
            <td><?php echo e($booking->currency.' '.number_format($booking->total_sales_cost, 2)); ?></td>
            <td><?php echo e($booking->currency.' '.number_format($booking->total_net_cost, 2)); ?></td>
            <td><?php echo e($booking->currency.' '.number_format($booking->deposite_amount,2)); ?></td>
            <td><?php echo e($booking->currency.' '.$booking->balance_amount); ?></td>
            <td><?php echo e($booking->currency.' '.number_format($booking->margin, 2)); ?></td>

            <td><?php echo e($booking->currency.' '.number_format($totalOtherCharges, 2)); ?></td>
            <td><?php echo e($booking->currency.' '.number_format($total_with_other_charges, 2)); ?></td>
            <td><?php echo e($booking->currency.' '.number_format($booking->refunded_amount, 2)); ?></td>
            <td><?php echo e($booking->currency.' '.number_format($profit_in_invoice, 2)); ?></td>

            <td><?php echo e($installmentPlan ? $booking->currency . ' ' . number_format($installmentPlan->amount, 2) : '-'); ?></td>
            <td><?php echo e($installmentPlan ? $installmentPlan->due_date : '-'); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/exports/bookings.blade.php ENDPATH**/ ?>