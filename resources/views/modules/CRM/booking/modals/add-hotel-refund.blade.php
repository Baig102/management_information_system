<form id="refundForm">

    @csrf
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Add Hotel Refund | Booking Number: {{ $booking->booking_prefix . $booking->booking_number }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">

        <pre>
            {{ print_r($booking->prices[4]) }}
        </pre>

        <h6 class="fw-bold text-primary">Hotel Amount Details </h6>
        <table class="table table-bordered table-nowrap table-sm">
            <thead>
                <tr>
                    <th scope="col">Total Sales Cost</th>
                    <th scope="col">Total Net Cost</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="text-success">{{ $booking->currency . ' ' . number_format($booking->prices[4]->total, 2) }}</span></td>
                    <td><span class="text-danger">{{ $booking->currency . ' ' . number_format($booking->prices[4]->net_total, 2) }}</span></td>
                    {{-- <td><span class="text-success">{{ $booking->currency . ' ' . number_format($booking->hotels->sum('sale_cost'), 2) }}</span></td>
                    <td><span class="text-danger">{{ $booking->currency . ' ' . number_format($booking->hotels->sum('net_cost'), 2) }}</span></td> --}}
                </tr>
            </tbody>
        </table>

        <h6 class="fw-bold text-primary">Hotel Refunded Details</h6>
        <!-- Bordered Tables -->
        <table class="table table-bordered table-nowrap">
            <thead>
                <tr>
                    <th scope="col">Sr #</th>
                    <th scope="col">Refunded Amount</th>
                    <th scope="col">Remaining Amount</th>
                    <th scope="col">Refund Method</th>
                    <th scope="col">Refund On</th>
                    <th scope="col">Refund Status</th>
                </tr>
            </thead>
            <tbody>
                @if (empty($booking->refunds))
                    @foreach ($booking->refunds as $payment)
                    <tr>
                        <th scope="row"> {{ $payment->installment_no == 0 ? 'Down Payment' : $payment->installment_no }}
                        </th>
                        <td><span class="text-success">{{ $booking->currency . ' ' . number_format($payment->reciving_amount, 2) }}</span> </td>
                        <td><span class="text-danger">{{ $booking->currency . ' ' . number_format($payment->remaining_amount, 2) }}</span> </td>
                        <td>
                            {{ $payment->payment_method }}
                        </td>
                        <td>{{ $payment->deposit_date }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr class="text-center">
                        <td colspan="8"> No Record Found</td>
                    </tr>
                @endif

            </tbody>
        </table>

        <h6 class="fw-bold text-primary">Add Hotel Refund</h6>
        {{-- @if ($booking->booking_payment_term === 1) --}}
            <!-- Warning Alert -->
            <div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show material-shadow"
                role="alert">
                <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - This booking is non-refundable...
            </div>
        {{-- @elseif ($booking->deposite_amount > 0) --}}
            <div class="row mb-3">
                @foreach ($booking->hotels as $hotl_key => $hotel)

                <div class="col-lg-2">
                    <!-- Custom Switches -->
                    <div class="form-check form-switch form-switch-custom form-switch-primary">
                        <input class="form-check-input" type="checkbox" name="bookingHotel[{{$hotel->id}}][id]" value="{{$hotel->id}}" role="switch" id="hotel_{{$hotel->id}}" {{ ($hotel->is_refunded == 1) ? "checked" : "" }}>
                        <label class="form-check-label" for="hotel_{{$hotel->id}}">{{ $hotel->hotel_name }}</label>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row mb-3">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Deposite Amount</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control paid_amount" id="paid_amount" name="paid_amount" value="{{ $booking->deposite_amount }}" placeholder="Enter Deposite Amount" step="0.01" readonly>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Service Charges</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control service_charges" id="service_charges" name="service_charges" value="0" placeholder="Enter Service Charges" step="0.01" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Refundable Amount</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control refundable_amount" id="refundable_amount" name="refundable_amount" value="{{ $booking->prices[4]->total-$booking->refunded_amount }}" placeholder="Enter Refundable Amount" step="0.01" required readonly>
                        {{-- <input type="number" class="form-control refundable_amount" id="refundable_amount" name="refundable_amount" value="{{ $booking->deposite_amount-$booking->refunded_amount }}" placeholder="Enter Refundable Amount" step="0.01" required readonly> --}}
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Refunded Amount</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control refunded_amount" id="refunded_amount" name="refunded_amount" value="0" placeholder="Enter Refunded Amount" step="0.01" max="{{ $booking->prices[4]->total-$booking->refunded_amount }}" required>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Refund Requested On</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                        <input type="text" name="refund_requeseted_on" value="{{ date('Y-m-d') }}" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="refund_requeseted_on" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-12">
                    <label>Refund Comments</label>
                    <textarea name="comments" id="comments" class="ckeditor-classic"></textarea>
                </div>
            </div>

            <h6 class="fw-bold text-primary">Refund Method</h6>
            <div class="flex-shrink-0 ms-2">
                {{-- <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist"> --}}
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
                            {{-- <select class="form-control ms select2" name="card_type" id="card_type" data-placeholder="Select Card Type">
                                <option></option>
                                <option value="American Express">American Express</option>
                                <option value="Debit Card">Debit Card</option>
                                <option value="Master Card">Master Card</option>
                                <option value="PayPal">PayPal</option>
                                <option value="Visa Card">Visa Card</option>
                            </select> --}}
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
                            {{-- <input type="text" class="form-control" id="cc-expiration" placeholder="MM/YY"> --}}
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
        {{-- @else --}}
            <!-- Warning Alert -->
            <div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show material-shadow"
                role="alert">
                <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - No payment received aginst this booking...
            </div>

        {{-- @endif --}}


    </div>
    <div class="modal-footer">
        {{-- @if ($booking->booking_payment_term === 2 && $booking->deposite_amount > 0) --}}
        <input type="hidden" name="refund_type" value="bookingHotel">
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        <button type="submit" class="btn btn-primary btn-sm" id="saveHotelRefund"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
        {{-- @endif --}}
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

        $('#service_charges').on('keyup', function () {
            var paid_amount = $('#paid_amount').val();
            if (parseInt(this.value) > parseInt(paid_amount)) {
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

                $('#service_charges').val('0.00');
                $('#refundable_amount').val('0.00');
                $('#refunded_amount').val(paid_amount);
                event.preventDefault();
                return;
            }
            var refundable_amount = parseInt(paid_amount)-parseInt(this.value);
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
                $.ajax({
                    type: 'POST',
                    url: "{{ route('crm.save-refund') }}",
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log(response);
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
