
<form id="installmentForm">
    <?php echo csrf_field(); ?>
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Edit Installment Plan | Booking Number:
            <?php echo e($booking->booking_prefix . $booking->booking_number); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">

        <h6 class="fw-bold text-primary"> Inovice Amount Details </h6>
        <table class="table table-bordered table-nowrap table-sm">
            <thead>
                <tr>
                    <th scope="col">Payment Type</th>
                    <th scope="col">Total Installments</th>
                    <th scope="col">Total Amount</th>
                    <th scope="col">Received Amount</th>
                    <th scope="col">Balance Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row"><?php echo e($booking->payment_type === 2 ? 'Installments' : 'Fully Paid'); ?></th>
                    <td><?php echo e($booking->total_installment); ?></td>
                    <td><span class="text-primary"><?php echo e($booking->currency . ' ' . $booking->total_sales_cost); ?></span></td>
                    <td><span class="text-success"><?php echo e($booking->currency . ' ' . $booking->deposite_amount); ?></span></td>
                    <td><span class="text-danger"><?php echo e($booking->currency . ' ' . $booking->balance_amount); ?></span>
                        <input type="hidden" name="balance_amount" value="<?php echo e($booking->balance_amount); ?>">
                    </td>
                    

                </tr>
            </tbody>
        </table>

        <h6 class="fw-bold text-primary">Received Payment Details</h6>
        <!-- Bordered Tables -->
        <table class="table table-bordered table-nowrap">
            <thead>
                <tr>
                    <th scope="col">Installment #</th>
                    <th scope="col">Received Amount</th>
                    <th scope="col">Remaining Amount</th>
                    <th scope="col">Payment Method</th>
                    <th scope="col">Payment On</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $booking->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th scope="row">
                            <?php echo e($payment->installment_no == 0 ? 'Down Payment' : $payment->installment_no); ?></th>
                        <td><span class="text-success"><?php echo e($booking->currency . ' ' . $payment->reciving_amount); ?></span>
                        </td>
                        <td><span class="text-danger"><?php echo e($booking->currency . ' ' . $payment->remaining_amount); ?></span>
                        </td>
                        <td><?php echo e($payment->payment_method); ?></td>
                        <td><?php echo e($payment->deposit_date); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>
        </table>

        <h6 class="fw-bold text-primary">Update Installment Plan</h6>
        <?php if($booking->payment_type === 1): ?>
            <!-- Warning Alert -->
            <div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show material-shadow"
                role="alert">
                <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - This booking does not support
                intallment plan...
            </div>
        <?php else: ?>
            <?php if($booking->installmentPlan->count() > 0): ?>
                <?php $__currentLoopData = $booking->installmentPlan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installmentPlan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row clearfix hotel_info mb-1 <?php echo e($installmentPlan->is_received == 1 ? 'bg-success-subtle' : ''); ?>" id="installment_plan">

                        <div class="col-lg-4 col-md-6">
                            <label>Installment Number</label>
                            <input type="text" class="form-control"
                                name="installment[<?php echo e($installmentPlan->id); ?>][installment_number]"
                                value="<?php echo e($installmentPlan->installment_number); ?>" placeholder="Installment Number" readonly>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label>Installment Due Date</label>
                            <input type="text" class="form-control <?php echo e($installmentPlan->is_received == 1 ? '' : 'flatpickr due_date flatpickr-date'); ?>"
                                data-provider="flatpickr" autocomplete="off" id="dueDate_<?php echo e($installmentPlan->id); ?>"
                                name="installment[<?php echo e($installmentPlan->id); ?>][due_date]" value="<?php echo e($installmentPlan->due_date); ?>"
                                placeholder="Installment Due Date" required <?php echo e($installmentPlan->is_received == 1 ? 'readonly' : ''); ?>>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <label>Installment Amount</label>
                            <input type="number" class="form-control <?php echo e($installmentPlan->is_received == 1 ? '' : 'installment_amount'); ?>"
                                name="installment[<?php echo e($installmentPlan->id); ?>][amount]" value="<?php echo e($installmentPlan->amount); ?>"
                                placeholder="Installment Amount" step="0.01" required="" <?php echo e($installmentPlan->is_received == 1 ? 'readonly' : ''); ?>>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
            <!-- Warning Alert -->
            <div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show material-shadow"
                role="alert">
                <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - Intallment plan not found, please first create installment plan...
            </div>
            <?php endif; ?>
        <?php endif; ?>


    </div>

    <div class="modal-footer">
        <?php if($booking->installmentPlan->count() > 0): ?>
        <input type="hidden" name="booking_id" value="<?php echo e($booking->id); ?>">
        <button type="submit" class="btn btn-primary btn-sm" id="updateInstallmentPlan"><i class="ri-save-3-line me-1 align-middle"></i>Update</button>
        <?php endif; ?>
        <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    </div>
</form>
<script>
    $(".flatpickr-date").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        //minDate: "today",
        //defaultDate: "today"
    });

    $(document).ready(function() {
        // Validate form before submitting
        $('#installmentForm').submit(function(event) {

            // Perform custom validation here

            var balance_amount = parseFloat($('[name="balance_amount"]').val()).toFixed(2);
            var booking_id = $('[name="booking_id"]').val();

            // Calculate total installment amount
            var totalInstallmentAmount = 0;
            $('.installment_amount').each(function() {
                totalInstallmentAmount += parseFloat($(this).val());
            });

            // console.log(balance_amount, (totalInstallmentAmount).toFixed(2) );
            // event.preventDefault();
            // return;
            //console.log(totalInstallmentAmount);
            // Validate total installment amount

            totalInstallmentAmount = (totalInstallmentAmount).toFixed(2);
            if (totalInstallmentAmount !== balance_amount) {
                //alert('Total installment amount must be equal to remaining booking amount.');
                var t;
                Swal.fire({
                    title: "Error!",
                    icon: "warning",
                    html: "Total installment amount must be equal to remaining booking amount.",
                    // timer: 2e3,
                    timer: 5000,
                    timerProgressBar: !0,
                    showCloseButton: !0,
                    didOpen: function() {
                        Swal.showLoading(), t = setInterval(function() {
                            var t = Swal.getHtmlContainer();
                            t && (t = t.querySelector("b")) && (t.textContent = Swal.getTimerLeft())
                        }, 100)
                    },
                    onClose: function() {
                        clearInterval(t)
                    }
                }).then(function(t) {
                    t.dismiss === Swal.DismissReason.timer //&& console.log("I was closed by the timer")
                })
                event.preventDefault();
                return;
            }else{
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('crm.update-installment-plan')); ?>",
                    data: $(this).serialize(),
                    success: function(response) {
                        //console.log(response);
                        $('.modal.extraLargeModal').modal('toggle');
                        $('.modal.fullscreeexampleModal').modal('toggle');
                        view_booking(booking_id);

                       Swal.fire({
                            title: response.title,
                            icon: response.icon,
                            text: response.message,
                        });
                    },
                    error: function(xhr, status, error) {
                        // Show error message
                        //alert(xhr.responseJSON.message);

                        Swal.fire({
                            //title: response.title,
                            //icon: response.icon,
                            text: xhr.responseJSON.message,
                        });
                    }
                });
                return;
            }
        });
    });


</script>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/edit-installment-plan.blade.php ENDPATH**/ ?>