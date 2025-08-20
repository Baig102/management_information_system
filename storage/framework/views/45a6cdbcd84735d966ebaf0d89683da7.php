<form id="refundForm">
    
    <?php echo csrf_field(); ?>
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Add Refund | Booking Number: <?php echo e($booking->booking_prefix . $booking->booking_number); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">

        <h6 class="fw-bold text-primary"> Inovice Amount Details </h6>
        <table class="table table-bordered table-nowrap table-sm">
            <thead>
                <tr>
                    <th scope="col">Payment Type</th>
                    <th scope="col">Total Installments</th>
                    <th scope="col">Total Sales Amount</th>
                    <th scope="col">Total Net Amount</th>
                    <th scope="col">Margin</th>
                    <th scope="col">Received Amount</th>
                    <th scope="col">Balance Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row"><?php echo e($booking->payment_type === 2 ? 'Installments' : 'Fully Paid'); ?></th>
                    <td><?php echo e($booking->total_installment); ?></td>
                    <td><span class="text-primary"><?php echo e($booking->currency . ' ' . number_format($booking->total_sales_cost, 2)); ?></span></td>
                    <td><span class="text-primary"><?php echo e($booking->currency . ' ' . number_format($booking->total_net_cost, 2)); ?></span></td>
                    <td><span class="text-primary"><?php echo e($booking->currency . ' ' . number_format($booking->margin, 2)); ?></span></td>
                    <td><span class="text-success"><?php echo e($booking->currency . ' ' . number_format($booking->deposite_amount, 2)); ?></span></td>
                    <td><span class="text-danger"><?php echo e($booking->currency . ' ' . number_format($booking->balance_amount, 2)); ?></span></td>
                    

                </tr>
            </tbody>
        </table>

        <h6 class="fw-bold text-primary">Refunded Details</h6>
        <!-- Bordered Tables -->
        <div class="table-responsive">
            <table class="table table-bordered table-nowrap">
                <thead>
                    <tr>
                        <th scope="col">Sr. Number</th>
                        <th scope="col">Refund Type</th>
                        <th scope="col">Received Amount</th>
                        <th scope="col">Refunded Amount</th>
                        <th scope="col">Service Charges</th>
                        <th scope="col">Remaining Amount</th>
                        <th scope="col">Refund Method</th>
                        <th scope="col">Refund Method Details</th>
                        <th scope="col">Refund Requested On</th>
                        <th scope="col">Refund Approve / Rejected On</th>
                        <th scope="col">Instant Refund</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($booking->refunds)): ?>
                        <?php $__currentLoopData = $booking->refunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ref_key => $refund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e($refund->refund_status === 1 ? "table-success" : (($refund->refund_status === 2) ? "table-danger" : "table-info")); ?>" id="refund_<?php echo e($refund->id); ?>">
                                <td>
                                    <a href="javascript:void(0)" class="fw-semibold"> <?php echo e($ref_key +1); ?> </a>
                                </td>
                                <td> <?php echo e($refund->refund_type." Refund"); ?> </td>
                                <td><span class="text-success"><?php echo e($booking->currency . ' ' . number_format($refund->paid_amount, 2)); ?></span></td>
                                <td><span class="text-danger"><?php echo e($booking->currency . ' ' . number_format($refund->refunded_amount, 2)); ?></span></td>
                                <td><span class="text-warning"><?php echo e($booking->currency . ' ' . number_format($refund->service_charges, 2)); ?></span></td>
                                <td><span class="text-danger"><?php echo e($booking->currency . ' ' . number_format($refund->remaining_amount, 2)); ?></span></td>
                                <td><?php echo e($refund->payment_method); ?></td>
                                <td>
                                    <?php if($refund->payment_method == 'Credit Debit Card'): ?>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs flex-shrink-0">
                                            <span class="avatar-title bg-light rounded-circle material-shadow">
                                                    <i class="bx bx-credit-card-alt h1 text-warning"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fs-14 mb-1"><?php echo e($refund->card_holder_name); ?></h6>
                                            <p class="text-muted fs-12 mb-0">
                                                <i class="mdi mdi-circle-medium text-success fs-15 align-middle"></i> <?php echo e($refund->card_number); ?>

                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 text-end">
                                            <h6 class="mb-1 text-warning"><span class="text-uppercase ms-1"><?php echo e($refund->card_type); ?></span></h6>
                                            <p class="text-muted fs-13 mb-0"><?php echo e($refund->card_expiry_date); ?> | <?php echo e($refund->card_cvc); ?></p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($refund->payment_method == 'Bank Transfer'): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs flex-shrink-0">
                                                <span class="avatar-title bg-light rounded-circle material-shadow">
                                                    <i class="ri-bank-line h1 text-primary"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="fs-14 mb-1"><?php echo e($refund->bank_name); ?></h6>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($refund->refund_requeseted_on); ?> / <?php echo e($refund->user->name); ?></td>
                                <td> <?php if($refund->refunded_on !=null): ?>
                                    <?php echo e($refund->refunded_on); ?> / <?php echo e(($refund->updated_by !=null) ? userDetails($refund->updated_by)->name : ""); ?>


                                    <?php else: ?>
                                    Pending
                                <?php endif; ?></td>
                                
                                <td><?php echo e(($refund->is_instant_refund == 1 ? "Yes" : "No")); ?></td>
                                <td>
                                    <?php if($refund->refund_status === 0): ?>
                                        Pending
                                    <?php elseif($refund->refund_status === 1): ?>
                                        Approved
                                    <?php elseif($refund->refund_status === 2): ?>
                                        Rejected
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr class="text-center">
                            <td colspan="7"> No Record Found</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
        <h6 class="fw-bold text-primary">Select Refund Type</h6>

            <div class="row mb-3">
                <div class="col-lg-2 col-sm-6">
                    <div class="form-check card-radio">
                        <input id="hotel_refund" name="refund_type" type="radio" class="form-check-input" value="bookingHotel">
                        <label class="form-check-label p-2" for="hotel_refund">
                            <span class="fs-16 text-muted me-2"><i class="ri-wallet-3-line align-bottom"></i></span>
                            <span class="fs-14 text-wrap">Hotel Refund</span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="form-check card-radio">
                        <input id="transport_refund" name="refund_type" type="radio" class="form-check-input" value="bookingTransport">
                        <label class="form-check-label p-2" for="transport_refund">
                            <span class="fs-16 text-muted me-2"><i class="ri-install-line align-bottom"></i></span>
                            <span class="fs-14 text-wrap">Transport Refund</span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="form-check card-radio">
                        <input id="flight_refund" name="refund_type" type="radio" class="form-check-input" value="bookingFlight">
                        <label class="form-check-label p-2" for="flight_refund">
                            <span class="fs-16 text-muted me-2"><i class="ri-install-line align-bottom"></i></span>
                            <span class="fs-14 text-wrap">Flight Refund</span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="form-check card-radio">
                        <input id="visa_refund" name="refund_type" type="radio" class="form-check-input" value="bookingVisa">
                        <label class="form-check-label p-2" for="visa_refund">
                            <span class="fs-16 text-muted me-2"><i class="ri-install-line align-bottom"></i></span>
                            <span class="fs-14 text-wrap">Visa Refund</span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="form-check card-radio">
                        <input id="full_refund" name="refund_type" type="radio" class="form-check-input" value="fullRefund" checked>
                        <label class="form-check-label p-2" for="full_refund">
                            <span class="fs-16 text-muted me-2"><i class="ri-install-line align-bottom"></i></span>
                            <span class="fs-14 text-wrap">Full Refund</span>
                        </label>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-check form-switch form-switch-custom form-switch-secondary">
                        <input class="form-check-input" type="checkbox" name="is_instant_refund" value="1" role="switch" id="is_instant_refund">
                        <label class="form-check-label" for="is_instant_refund">Instant Refund</label>
                    </div>
                </div>
            </div>

            <div class="row" id="refundData"></div>
            <hr>
            <div class="row" id="pricingData">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-nowrap table-sm" id="pricingDataTable">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Booking Type</th>
                                    <th>Sales Cost</th>
                                    <th>Net Cost</th>
                                    <th>Quantity</th>
                                    <th>Total Sale Cost</th>
                                    <th>Total Net Cost</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr>
            <h6 class="fw-bold text-primary">Add Refund</h6>
            <div class="row mb-3">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Deposite Amount</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control paid_amount" id="paid_amount" name="paid_amount" value="<?php echo e($booking->deposite_amount-$booking->refunded_amount); ?>" placeholder="Enter Deposite Amount" step="0.01" readonly>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Margin</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control margin_value" id="margin_value" name="margin_value" value="<?php echo e($booking->margin); ?>" step="0.01" readonly>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Ailine Penalty</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control airline_penalty" id="airline_penalty" name="airline_penalty" value="0" placeholder="Enter Ailine Penalty" step="0.01" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Refund Charges</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control refund_charges" id="refund_charges" name="refund_charges" value="0" placeholder="Enter Refund Charges" step="0.01" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Supplier Fee</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control supplier_fee" id="supplier_fee" name="supplier_fee" value="0" placeholder="Enter Supplier Fee" step="0.01" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Total Service Charges</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control service_charges" id="service_charges" name="service_charges" value="0" placeholder="Enter Service Charges" step="0.01" readonly required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Refundable Amount</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control refundable_amount" id="refundable_amount" name="refundable_amount" value="<?php echo e($booking->deposite_amount-$booking->refunded_amount-$booking->margin); ?>" placeholder="Enter Refundable Amount" step="0.01" required readonly>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Refunded Amount</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control refunded_amount" id="refunded_amount" name="refunded_amount" value="0" placeholder="Enter Refunded Amount" step="0.01" max="<?php echo e($booking->deposite_amount-$booking->refunded_amount-$booking->margin); ?>" readonly required>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Refund Requested On</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                        <input type="text" name="refund_requeseted_on" value="<?php echo e(date('Y-m-d')); ?>" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="refund_requeseted_on" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-12">
                    <label>Refund Comments</label>
                    <textarea name="comments" id="comments" class="form-control"></textarea>
                </div>
            </div>

            <h6 class="fw-bold text-primary">Refund Method</h6>
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
                            <input type="number" name="cc-ccc" class="form-control" id="cc-ccc" value="0.00" step="0.01" readonly>
                        </div>
                    </div>
                </div>
            </div>

    </div>
    <div class="modal-footer">
        <input type="hidden" name="booking_id" value="<?php echo e($booking->id); ?>">
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
        $('input[name="refund_type"]').change(function() {
            var refundType = $(this).val();
            var bookingId = $('input[name="booking_id"]').val();
            var paid_amount = $('input[name="paid_amount"]').val();
            //console.log(refundType);
            $('#refundData').html('');

            $.ajax({
                url: '<?php echo e(route("crm.get-data-for-refund")); ?>',
                type: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    refund_type: refundType,
                    booking_id: bookingId
                },
                beforeSend: function() {
                    // Show loader
                    $('#loader').show();
                },
                success: function(data) {
                    //console.log(data);
                    /* var response = JSON.stringify(data);
                    console.log(JSON.parse(data));
                    $('#refundData').html(JSON.stringify(data)); */
                    // Assuming data is an array of objects
                    var htmlContent = '';
                    var pricingHtmlContent = '';
                    var redundData = data[0];
                    var pricing = data[1];
                    var total_cost = 0;
                    //console.log(pricing);
                    redundData.forEach(function(item) {
                        //console.log(item);
                        htmlContent += '<div class="col-lg-2"> <div class="form-check form-switch form-switch-custom form-switch-primary"> <input class="form-check-input" type="checkbox" name="'+item.data_model+'['+ item.id +'][id]" value="'+ item.id +'" role="switch" id="'+item.data_model+'_'+ item.id +'"> <label class="form-check-label" for="'+item.data_model+'_'+ item.id +'">'+ item.name +'</label> </div> </div>';
                    });
                    $('#refundData').html(htmlContent);

                    pricing.forEach(function(pItem) {
                        //console.log(pItem);
                        total_cost = total_cost + +pItem.total
                        pricingHtmlContent += '<tr><td>'+pItem.id+'</td><td>'+pItem.booking_type+'</td><td>'+pItem.sale_cost+'</td><td>'+pItem.net_cost+'</td><td>'+pItem.quantity+'</td><td>'+pItem.total+'</td><td>'+pItem.net_total+'</td></tr>';
                    });
                    $('#pricingData #pricingDataTable tbody').html(pricingHtmlContent);
                    if (refundType !=='fullRefund') {
                        if (paid_amount >= total_cost) {
                            //then total sales cost is max values
                            $('#refunded_amount').attr('max', total_cost)
                        }else{
                            //then paid amount is max value
                            $('#refunded_amount').attr('max', paid_amount)
                        }
                    } else {
                        $('#refunded_amount').attr('max', paid_amount)
                    }


                    //console.log(total_cost);

                },
                complete: function() {
                    // Hide loader
                    $('#loader').hide();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    //$('#refundData').html(xhr.responseText);

                }
            });
        });
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
                    var refunded_amount = $('#refunded_amount').val();
                    // $('#cc-ccc').val((refunded_amount*0.03).toFixed(2));

                } else {
                    $('#credit_debit_card_transfer input').each(function() {
                        // Do something with each input field
                        $(this).val('');
                    });
                }
            }
        });

        function updateServiceChargesFromInputs() {
            var airline_penalty = parseFloat($('#airline_penalty').val()) || 0;
            var refund_charges = parseFloat($('#refund_charges').val()) || 0;
            var supplier_fee = parseFloat($('#supplier_fee').val()) || 0;

            var total_service_charges = airline_penalty + refund_charges + supplier_fee;

            $('#service_charges').val(total_service_charges.toFixed(2)).trigger('keyup');
        }

        // Trigger calculation when any of the three inputs change
        $('#airline_penalty, #refund_charges, #supplier_fee').on('input', function () {
            updateServiceChargesFromInputs();
        });

        $('#service_charges').on('keyup', function () {
            var paid_amount = $('#paid_amount').val();
            var margin_value = $('#margin_value').val();
            var service_charges = this.value;
            var refuned_able_amount = parseInt(paid_amount)-parseInt(margin_value);
            // console.log(paid_amount, margin_value, service_charges, refuned_able_amount);
            if (parseInt(service_charges) > parseInt(refuned_able_amount)) {
                var t;
                Swal.fire({
                    title: "Error!",
                    icon: "warning",
                    html: "Service charges must not be greater then actuall paid amount.",
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
                // Reset all service charge-related fields
                $('#airline_penalty, #refund_charges, #supplier_fee, #service_charges, #refundable_amount, #refunded_amount').val('0.00');
                // $('#service_charges').val('0.00');
                // $('#refundable_amount').val('0.00');
                // $('#refunded_amount').val(refuned_able_amount);
                event.preventDefault();
                return;
            }
            var refundable_amount = parseInt(refuned_able_amount)-parseInt(service_charges);
            $('#refundable_amount').val(refundable_amount);
            $('#refunded_amount').val(refundable_amount);

        });

        $('#refunded_amount').on('keyup', function () {
            var paid_amount = $('#paid_amount').val();
            var service_charges = $('#service_charges').val();
            var check_value = parseInt(paid_amount) - parseInt(service_charges);
            if (this.value > check_value) {
                var t;
                Swal.fire({
                    title: "Error!",
                    icon: "warning",
                    html: "Refundable amount must be equal or less to "+check_value,
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

                //$('#service_charges').val('0.00');
                //$('#refundable_amount').val('0.00');
                $('#refunded_amount').val(check_value);
                event.preventDefault();
                return;
            }
            //$('#refunded_amount').val(check_value);

        })

        // Validate form before submitting
        $('#refundForm').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            //var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var booking_id = $('[name="booking_id"]').val();
            event.preventDefault();

            if ($('#refunded_amount').val() == 0) {
                Swal.fire({
                    title: "Error!",
                    icon: "warning",
                    html: "Refunded amount must be greater than 0.",
                    timer: 5000,
                    timerProgressBar: !0,
                    showCloseButton: !0,
                })
                return;
            }

            Swal.fire({
            title: "Do you want to add refund?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Yes",
            //denyButtonText: `Don't save`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo e(route('crm.save-refund')); ?>",
                        data: $(this).serialize(),
                        success: function(response) {
                            console.log(response);
                            if (response.code == 200) {
                                $('.modal.extraLargeModal').modal('toggle');
                                // $('.modal.fullscreeexampleModal').modal('toggle');
                                // view_booking(booking_id);
                                // Remove the row from report table
                                $('#row' + booking_id).remove();

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
                                title: 'Error',
                                icon: 'error',
                                text: xhr.responseJSON.message,
                            });
                        }
                    });
                }
                return;
            });
        });
    });


</script>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/add-refund.blade.php ENDPATH**/ ?>