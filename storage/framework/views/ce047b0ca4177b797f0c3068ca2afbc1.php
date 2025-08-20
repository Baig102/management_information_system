
<form id="paymentForm">
    <?php echo csrf_field(); ?>
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Add Payment | Booking Number:
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
                    <td><span class="text-primary"><?php echo e($booking->currency . ' ' . number_format($booking->total_sales_cost, 2)); ?></span></td>
                    <td><span class="text-success"><?php echo e($booking->currency . ' ' . number_format($booking->deposite_amount, 2)); ?></span></td>
                    <td><span class="text-danger"><?php echo e($booking->currency . ' ' . number_format($booking->balance_amount, 2)); ?></span></td>
                    

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
                    <?php if(empty($payment->other_charges_id)): ?>
                    <tr>
                        <th scope="row"> <?php echo e($payment->installment_no == 0 ? 'Down Payment' : $payment->installment_no); ?>

                        </th>
                        <td><span class="text-success"><?php echo e($booking->currency . ' ' . number_format($payment->reciving_amount, 2)); ?></span> </td>
                        <td><span class="text-danger"><?php echo e($booking->currency . ' ' . number_format($payment->remaining_amount, 2)); ?></span> </td>
                        <td>
                            <?php echo e($payment->payment_method); ?>

                            
                        </td>
                        <td><?php echo e($payment->deposit_date); ?></td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>
        </table>

        <h6 class="fw-bold text-primary">Add Payment</h6>
        <?php if($booking->payment_type === 1): ?>

            <!-- Warning Alert -->
            <div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show material-shadow"
                role="alert">
                <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - This booking does not support multiple payments...
            </div>
        <?php else: ?>

            <?php if($booking->payments->contains('is_approved', 0)): ?>
                <div class="alert alert-danger alert-border-left alert-dismissible fade show mb-xl-0 material-shadow" role="alert">
                    <i class="ri-error-warning-line me-3 align-middle fs-16"></i><strong>Error!</strong> - Please approve the previous payment before adding the new payment...
                </div>

            <?php elseif(round($totalInstallmentAmount, 2) !== round($booking->balance_amount, 2)): ?>
                <div class="alert alert-danger alert-border-left alert-dismissible fade show mb-xl-0 material-shadow" role="alert">
                    <i class="ri-error-warning-line me-3 align-middle fs-16"></i><strong>Error!</strong> - Please update the installment plan, as booking pricing has been updated...
                </div>
            <?php elseif($booking->total_installment != $totalInstallemts): ?>
                <div class="alert alert-danger alert-border-left alert-dismissible fade show mb-xl-0 material-shadow" role="alert">
                    <i class="ri-error-warning-line me-3 align-middle fs-16"></i><strong>Error!</strong> - Please update the installment plan, as number of installments has been updated...
                </div>
            <?php else: ?>

                <?php if($pendingInstallment->count() > 0): ?>

                    <?php $__currentLoopData = $pendingInstallment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment_plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>Installment Number</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control installment_number" id="installment_number" name="installment_number" value="<?php echo e($installment_plan->installment_number); ?>" placeholder="Enter Deposite Amount" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>Installment Amount</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control installment_amount" id="installment_amount" name="installment_amount" value="<?php echo e($installment_plan->amount); ?>" placeholder="Enter Installment Amount" step="0.01" required readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>Due Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="due_date" value="<?php echo e($installment_plan->due_date); ?>" placeholder="DD-MM-YYYY" class="form-control" id="due_date" autocomplete="off" readonly required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>Deposit Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="received_on" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="received_on" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>Receipt Voucher</label>
                            <div class="input-group">
                                <input class="form-control" type="file" id="formFile" name="receipt_voucher">
                            </div>
                        </div>
                        <input type="hidden" name="installment_id" id="" value="<?php echo e($installment_plan->id); ?>">
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <label>Payment Comments</label>
                            <textarea name="comments" id="comments" class="form-control"></textarea>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    

                    <hr>
                    <h6 class="fw-bold text-primary">Payment Method</h6>
                    <div class="flex-shrink-0 ms-2">
                        <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item" role="presentation">
                                <div data-bs-toggle="tab" data-bs-target="#cash_transfer" role="tab" aria-selected="false">
                                    <div class="form-check card-radio">
                                        <input id="cash_transfer_label" name="payment_method" type="radio" class="form-check-input" value="Cash">
                                        <label class="form-check-label p-2" for="cash_transfer_label">
                                            <span class="fs-16 text-muted me-2"><i class="ri-money-pound-circle-line align-bottom"></i></span>
                                            <span class="fs-14 text-wrap">Cash</span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item" role="presentation">
                                <div data-bs-toggle="tab" data-bs-target="#bank_transfer" role="tab" aria-selected="true">
                                    <div class="form-check card-radio">
                                        <!-- Set 'checked' attribute here -->
                                        <input id="bank_transfer_label" name="payment_method" type="radio" value="Bank Transfer" class="form-check-input" checked>
                                        <label class="form-check-label p-2" for="bank_transfer_label">
                                            <span class="fs-16 text-muted me-2"><i class="ri-bank-line align-bottom"></i></span>
                                            <span class="fs-14 text-wrap">Bank Transfer</span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item" role="presentation">
                                <div data-bs-toggle="tab" data-bs-target="#credit_limit_transfer" role="tab" aria-selected="false" tabindex="-1">
                                    <div class="form-check card-radio">
                                        <input id="credit_limit_transfer_label" name="payment_method" type="radio" value="Credit Limit" class="form-check-input">
                                        <label class="form-check-label p-2" for="credit_limit_transfer_label">
                                            <span class="fs-16 text-muted me-2"><i class="ri-secure-payment-line align-bottom"></i></span>
                                            <span class="fs-14 text-wrap">Credit Limit</span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item" role="presentation">
                                <div data-bs-toggle="tab" data-bs-target="#credit_debit_card_transfer" role="tab" aria-selected="false" tabindex="-1">
                                    <div class="form-check card-radio">
                                        <input id="credit_debit_card_transfer_label" name="payment_method" value="Credit Debit Card" type="radio" class="form-check-input">
                                        <label class="form-check-label p-2" for="credit_debit_card_transfer_label">
                                            <span class="fs-16 text-muted me-2"><i class="ri-bank-card-fill align-bottom"></i></span>
                                            <span class="fs-14 text-wrap">Credit / Debit Card</span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab panes -->
                    

                    <div class="tab-content">
                        <!-- Removed 'show active' from other tabs and added it to 'bank_transfer' -->
                        <div class="tab-pane show active" id="bank_transfer" role="tabpanel">
                            <div class="row gy-3">
                                <div class="col-lg-4">
                                    <label for="cc-name" class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" id="cc-name" placeholder="Enter name">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="cash_transfer" role="tabpanel">
                            <!-- Content for Cash Transfer, if any -->
                        </div>

                        <div class="tab-pane" id="credit_debit_card_transfer" role="tabpanel">
                            <div class="row gy-3">
                                <div class="col-lg-2">
                                    <label for="card_type" class="form-label">Credit Or Debit</label>
                                    <select class="form-select" name="card_type_type" id="card_type_type">
                                        <option>Credit Card</option>
                                        <option>Debit Card</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="card_type" class="form-label">Card Type</label>
                                    <input type="text" name="card_type" class="form-control" id="card_type" placeholder="Card Type" readonly>
                                </div>
                                <div class="col-lg-2">
                                    <label for="cc-name" class="form-label">Name on card</label>
                                    <input type="text" name="cc-name" class="form-control" id="cc-name" placeholder="Enter name">
                                    <small class="text-danger">Full name as displayed on card</small>
                                </div>
                                <div class="col-lg-2">
                                    <label for="cc-number" class="form-label">Card number</label>
                                    <input type="text" name="cc-number" class="form-control" id="cc-number" placeholder="xxxx xxxx xxxx xxxx">
                                </div>
                                <div class="col-lg-2">
                                    <label for="cc-expiration" class="form-label">Expiration</label>
                                    <input type="text" name="cc-expiration" class="form-control" placeholder="MM/YY" id="cc-expiration">
                                </div>
                                <div class="col-lg-2">
                                    <label for="cc-cvv" class="form-label">CVV</label>
                                    <input type="text" name="cc-cvv" class="form-control" id="cc-cvv" placeholder="xxx" max="3">
                                </div>
                                <div class="col-lg-2">
                                    <label for="cc-ccc" class="form-label">CC Charges</label>
                                    <input type="number" name="cc-ccc" class="form-control" id="cc-ccc" placeholder="xxx" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Warning Alert -->
                    <div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show material-shadow"
                        role="alert">
                        <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - No pending payment found...
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>


    </div>
    <div class="modal-footer">
        <?php if($pendingInstallment->count() > 0 && round($totalInstallmentAmount, 2) == round($booking->balance_amount, 2)): ?>
        <input type="hidden" name="booking_id" value="<?php echo e($booking->id); ?>">
        <input type="hidden" name="total_sales_cost" value="<?php echo e($booking->total_sales_cost); ?>">
        <input type="hidden" name="total_deposite_amount" value="<?php echo e($booking->deposite_amount); ?>">
        <input type="hidden" name="total_balance_amount" value="<?php echo e($booking->balance_amount); ?>">
        <button type="submit" class="btn btn-primary btn-sm" id="saveInstallmentPlan"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
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
        defaultDate: "today"
    });

    $(document).ready(function () {
        // Show the corresponding tab when a radio button is clicked
        $('input[name="payment_method"]').on('change', function () {
            var target = $(this).closest('div[data-bs-target]').data('bs-target'); // Get the target tab content ID
            // Hide all tab panes
            $('.tab-pane').removeClass('show active');
            // Show the selected tab pane
            $(target).addClass('show active');
        });
    });

    var cleave = new Cleave('#cc-number', {
        creditCard: true,
        onCreditCardTypeChanged: function (type) {
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
        .create( document.querySelector( '#comments' ) )
        .catch( error => {
            console.error( error );
        } )

        // Listen for click event on radio buttons
        $('input[type="radio"]').on('click', function() {
            // Check if the clicked radio button is checked
            if ($(this).is(':checked')) {
                // Get the value of the clicked radio button
                var radioValue = $(this).val();
                if (radioValue == 'Credit Debit Card') {
                    var installment_amount = $('#installment_amount').val();
                    $('#cc-ccc').val((installment_amount*0.03).toFixed(2));

                } else {
                    $('#credit_debit_card_transfer input').each(function() {
                        // Do something with each input field
                        $(this).val('');
                    });
                }
            }
        });

        /* // Validate form before submitting
        $('#paymentForm').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            //var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var booking_id = $('[name="booking_id"]').val();

            event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('crm.save-payment')); ?>",
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
                return;
        }); */

        // Validate form before submitting
        $('#paymentForm').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            //var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var booking_id = $('[name="booking_id"]').val();
            // Create a FormData object to handle file upload and other data
            var formData = new FormData(this);

            event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('crm.save-payment')); ?>",
                    data: formData,
                    contentType: false, // Prevent jQuery from setting the Content-Type header
                    processData: false, // Prevent jQuery from processing the data (important for file uploads)
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
                return;
        });
    });


</script>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/add-payment.blade.php ENDPATH**/ ?>