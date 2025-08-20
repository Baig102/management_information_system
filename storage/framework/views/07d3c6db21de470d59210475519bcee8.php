
<form id="paymentForm">
    <?php echo csrf_field(); ?>
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Add Other Charges Payment | Booking Number: <?php echo e($booking->booking_prefix . $booking->booking_number); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        
        <h6 class="fw-bold text-primary"> Other Charges Amount Details </h6>
        <table class="table table-bordered table-nowrap table-sm">
            <thead>
                <tr>
                    <th scope="col">Charges Type</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Reciving Amount</th>
                    <th scope="col">Remaining Amount</th>
                    <th scope="col">Comments</th>
                    <th scope="col">Added On</th>
                </tr>
            </thead>
            <tbody>
                <?php if($otherCharges->count() <= 0): ?>
                <tr>
                    <td colspan="6"> No Record Found</td>
                </tr>
                <?php else: ?>
                <tr>
                    <td><?php echo e($otherCharges->charges_type); ?></td>
                    <td><span class="text-primary"><?php echo e($booking->currency . ' ' . number_format($otherCharges->amount, 2)); ?></span></td>
                    <td><span class="text-success"><?php echo e($booking->currency . ' ' . number_format($otherCharges->reciving_amount, 2)); ?></span></td>
                    <td><span class="text-danger"><?php echo e($booking->currency . ' ' . number_format($otherCharges->remaining_amount, 2)); ?></span></td>
                    <td><?php echo e($otherCharges->comments); ?></td>
                    <td><?php echo e($otherCharges->charges_at); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h6 class="fw-bold text-primary">Received Other Charges Payment Details</h6>
        <!-- Bordered Tables -->
        <table class="table table-bordered table-nowrap table-sm">
            <thead>
                <tr>
                    <th scope="col">Sr #</th>
                    <th scope="col">Received Amount</th>
                    <th scope="col">Remaining Amount</th>
                    <th scope="col">Payment Method</th>
                    <th scope="col">Payment On</th>
                </tr>
            </thead>
            <tbody>
                <?php if($otherChargesPayments->count() <= 0): ?>
                <tr class="text-center">
                    <td colspan="5">No Record Found</td>
                </tr>
                <?php else: ?>
                    <?php $__currentLoopData = $otherChargesPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oc_key => $other_charges): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row"> <?php echo e($oc_key+1); ?> </th>
                            <td><span class="text-success"><?php echo e($booking->currency . ' ' . number_format($other_charges->reciving_amount, 2)); ?></span> </td>
                            <td><span class="text-danger"><?php echo e($booking->currency . ' ' . number_format($other_charges->remaining_amount, 2)); ?></span> </td>
                            <td>
                                <?php echo e($other_charges->payment_method); ?>

                            </td>
                            <td><?php echo e($other_charges->deposit_date); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h6 class="fw-bold text-primary">Add Other Charges Payment</h6>
        <?php if($otherCharges->payment_status === 1): ?>

            <!-- Warning Alert -->
            <div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show material-shadow" role="alert">
                <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - This already fully paid...
            </div>

        <?php else: ?>

            <div class="row mb-3">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Amount</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control amount" id="amount" name="amount" value="<?php echo e($otherCharges->amount); ?>" placeholder="Enter Amount" readonly>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Reciving Amount</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control reciving_amount" id="reciving_amount" name="reciving_amount" value="" placeholder="Enter Reciving Amount" step="0.01" required max="<?php echo e($otherCharges->remaining_amount); ?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Deposit Date</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                        <input type="text" name="received_on" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="received_on" autocomplete="off">
                    </div>
                </div>

            </div>

            <div class="row mb-3">
                <div class="col-lg-12">
                    <label>Payment Comments</label>
                    <textarea name="comments" id="comments" class="ckeditor-classic"></textarea>
                </div>
            </div>

            <h6 class="fw-bold text-primary">Payment Method</h6>
            <div class="flex-shrink-0 ms-2">
                
                <ul class="list-group list-group-horizontal-md nav" role="tablist">
                    <li class="nav-item m-1" role="presentation">

                        <div data-bs-toggle="tab" data-bs-target="#cash_transfer" role="tab" aria-selected="true">
                            <div class="form-check card-radio">
                                <input id="cash_transfer_label" name="payment_method" type="radio" class="form-check-input" value="Cash" checked>
                                <label class="form-check-label p-2" for="cash_transfer_label">
                                    <span class="fs-16 text-muted me-2"><i class="ri-money-pound-circle-line align-bottom"></i></span>
                                    <span class="fs-14 text-wrap">Cash</span>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item m-1" role="presentation">
                        <div data-bs-toggle="tab" data-bs-target="#bank_transfer" role="tab" aria-selected="false" tabindex="-1">
                            <div class="form-check card-radio">
                                <input id="bank_transfer_label" name="payment_method" type="radio" value="Bank Transfer" class="form-check-input">
                                <label class="form-check-label p-2" for="bank_transfer_label">
                                    <span class="fs-16 text-muted me-2"><i class="ri-bank-line align-bottom"></i></span>
                                    <span class="fs-14 text-wrap">Bank Transfer</span>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item m-1" role="presentation">
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
                    <li class="nav-item m-1" role="presentation">
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
            <div class="tab-content mt-3">
                <div class="tab-pane" id="bank_transfer" role="tabpanel">
                    <div class="row gy-3">
                        <div class="col-lg-4">
                            <label for="cc-name" class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" id="cc-name" placeholder="Enter name">
                        </div>
                    </div>
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
                            
                            <input type="text" name="cc-expiration" class="form-control" placeholder="MM/YY" id="cc-expiration" max="5" maxlength="6">
                        </div>

                        <div class="col-lg-2">
                            <label for="cc-cvv" class="form-label">CVV</label>
                            <input type="text" name="cc-cvv" class="form-control" id="cc-cvv" placeholder="xxx" max="3">
                        </div>

                        <div class="col-lg-2">
                            <label for="cc-ccc" class="form-label">CC Charges <small class="text-danger"> (3%)</small></label>
                            <input type="number" name="cc-ccc" class="form-control" id="cc-ccc" value="0.00" step="0.01">
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
    <div class="modal-footer">
        <input type="hidden" name="booking_id" value="<?php echo e($booking->id); ?>">
        <input type="hidden" name="other_charges_id" id="" value="<?php echo e($otherCharges->id); ?>">
        <button type="submit" class="btn btn-primary btn-sm" id="saveInstallmentPlan"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
        <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    </div>
</form>


<script>
    $(".flatpickr-date").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        minDate: "today",
        defaultDate: "today"
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
                    var reciving_amount = $('#reciving_amount').val();
                    $('#cc-ccc').val((reciving_amount*0.03).toFixed(2));

                } else {
                    $('#credit_debit_card_transfer input').each(function() {
                        // Do something with each input field
                        $(this).val('');
                    });
                }
            }
        });

        // Validate form before submitting
        $('#paymentForm').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            //var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var booking_id = $('[name="booking_id"]').val();

            event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('crm.save-oc-payment')); ?>",
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
                        console.log(xhr, status, error);
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
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/add-oc-payment.blade.php ENDPATH**/ ?>