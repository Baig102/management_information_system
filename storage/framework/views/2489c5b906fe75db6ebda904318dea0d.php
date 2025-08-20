<!-- resources/views/exports/bookings.blade.php -->

<table>
    <thead>
        <tr>
            <th><strong>INVOICE #</strong></th>
            <th><strong>PAYMENT TERM</strong></th>
            <th><strong>TOTAL</strong></th>
            <th><strong>RECEIVING AMOUNT</strong></th>
            <th><strong>REMAINING AMOUNT</strong></th>
            <th><strong>INSTALLMENT NUMBER</strong></th>
            <th><strong>PAYMENT METHOD</strong></th>
            <th><strong>PAYMENT METHOD DETAILS</strong></th>
            <th><strong>PAYMENT ON</strong></th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($payment->booking->booking_prefix.$payment->booking->booking_number); ?></td>
            <td><?php echo e($payment->booking->booking_payment_term == 2 ? "Full Payment" : "Installment"); ?></td>
            <td><?php echo e($payment->booking->currency.' '.number_format($payment->booking->total_sales_cost, 2)); ?></td>
            <td><?php echo e($payment->booking->currency.' '.number_format($payment->reciving_amount, 2)); ?></td>
            <td><?php echo e($payment->booking->currency.' '.number_format($payment->remaining_amount, 2)); ?></td>
            <td><?php echo e($payment->installment_no); ?></td>
            <td><?php echo e($payment->payment_method); ?></td>
            <td>
                <?php echo e($payment->payment_method); ?>

                <?php if($payment->payment_method == 'Credit Debit Card'): ?>
                <p><?php echo e($payment->card_holder_name); ?></p>
                <p><?php echo e($payment->card_number); ?></p>
                <p><?php echo e($payment->card_type); ?></p>
                <p><?php echo e($payment->card_expiry_date); ?> | <?php echo e('xxx'); ?></p>
                <?php endif; ?>
                <?php if($payment->payment_method == 'Bank Transfer'): ?>
                    <p><?php echo e($payment->bank_name); ?></p>
                <?php endif; ?>
            </td>
            <td><?php echo e($payment->payment_on); ?></td>

        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/exports/payments.blade.php ENDPATH**/ ?>