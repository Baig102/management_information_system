{{-- <form action="{{ route('crm.save-installment-plan') }}" method="post" id="paymentForm" enctype="multipart/form-data"> --}}
<form id="paymentForm">
    @csrf
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Edit Payment | Booking Number:
            {{ $booking->booking_prefix . $booking->booking_number }}</h5>
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
                    <th scope="row">{{ $booking->payment_type === 2 ? 'Installments' : 'Fully Paid' }}</th>
                    <td>{{ $booking->total_installment }}</td>
                    <td><span class="text-primary">{{ $booking->currency . ' ' . $booking->total_sales_cost }}</span></td>
                    <td><span class="text-success">{{ $booking->currency . ' ' . $booking->deposite_amount }}</span></td>
                    <td><span class="text-danger">{{ $booking->currency . ' ' . $booking->balance_amount }}</span></td>
                    {{-- <td><span class="badge bg-primary-subtle text-primary">Backlog</span></td> --}}

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
                @foreach ($booking->payments as $payment)
                    <tr>
                        <th scope="row"> {{ $payment->installment_no == 0 ? 'Down Payment' : $payment->installment_no }}</th>
                        <td><span class="text-success">{{ $booking->currency . ' ' . $payment->reciving_amount }}</span> </td>
                        <td><span class="text-danger">{{ $booking->currency . ' ' . $payment->remaining_amount }}</span> </td>
                        <td>
                            {{ $payment->payment_method }}
                            {{-- <button type="button" class="btn btn-light" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
                                    Popover on bottom
                                </button> --}}
                        </td>
                        <td>{{ $payment->deposit_date }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        <h6 class="fw-bold text-primary">Edit Payment</h6>
        {{-- <pre>{{ print_r($installment) }}</pre> --}}
        @if ($installment->count() > 0)


            <div class="row mb-3">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Installment Number</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control installment_number" id="installment_number" name="installment_number" value="{{ $installment->installment_no }}" placeholder="Enter Deposite Amount" readonly>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Installment Amount</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control installment_amount" id="installment_amount" name="installment_amount" value="{{ $installment->reciving_amount }}" placeholder="Enter Installment Amount" step="0.01" required readonly>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Due Date</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                        <input type="text" name="due_date" value="{{ $installment->due_date }}" placeholder="DD-MM-YYYY" class="form-control" id="due_date" autocomplete="off" readonly required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Deposit Date</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                        <input type="text" name="received_on" value="{{ $installment->deposit_date }}" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="received_on" autocomplete="off">
                    </div>
                </div>
                <input type="hidden" name="installment_id" id="" value="{{ $installment->id }}">
            </div>

            <h6 class="fw-bold text-primary">Payment Method</h6>
            <div class="flex-shrink-0 ms-2">
                {{-- <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist"> --}}
                <ul class="list-group list-group-horizontal-md nav" role="tablist">
                    <li class="nav-item m-1" role="presentation">

                        <div data-bs-toggle="tab" data-bs-target="#cash_transfer" role="tab" aria-selected="true">
                            <div class="form-check card-radio">
                                <input id="cash_transfer_label" name="payment_method" type="radio" class="form-check-input" value="Cash" {{ $installment->payment_method =='Cash' ? "checked" : "" }}>
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
                                <input id="bank_transfer_label" name="payment_method" type="radio" value="Bank Transfer" class="form-check-input" {{ $installment->payment_method =='Bank Transfer' ? "checked" : "" }}>
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
                                <input id="credit_limit_transfer_label" name="payment_method" type="radio" value="Credit Limit" class="form-check-input" {{ $installment->payment_method =='Credit Limit' ? "checked" : "" }}>
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
                                <input id="credit_debit_card_transfer_label" name="payment_method" value="Credit Debit Card" type="radio" class="form-check-input" {{ $installment->payment_method =='Credit Debit Card' ? "checked" : "" }}>
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
                <div class="tab-pane {{ $installment->payment_method =='Bank Transfer' ? "active show" : "" }}" id="bank_transfer" role="tabpanel">
                    <div class="row gy-3">
                        <div class="col-lg-4">
                            <label for="bank-name" class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" id="bank-name" value="{{ $installment->bank_name }}" placeholder="Enter name">
                        </div>
                    </div>
                </div>

                <div class="tab-pane {{ $installment->payment_method =='Credit Debit Card' ? "active show" : "" }}" id="credit_debit_card_transfer" role="tabpanel">
                    <div class="row gy-3">
                        <div class="col-lg-2">
                            <label for="card_type" class="form-label">Card Type</label>
                            <input type="text" name="card_type" class="form-control" id="card_type" value="{{ $installment->card_type }}" placeholder="Card Type" readonly>
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
                            <input type="text" name="cc-name" class="form-control" id="cc-name" value="{{ $installment->card_holder_name }}" placeholder="Enter name">
                            <small class="text-danger">Full name as displayed on card</small>
                        </div>

                        <div class="col-lg-2">
                            <label for="cc-number" class="form-label">Card number</label>
                            <input type="text" name="cc-number" class="form-control" id="cc-number" value="{{ $installment->card_number }}" placeholder="xxxx xxxx xxxx xxxx">
                        </div>

                        <div class="col-lg-2">
                            <label for="cc-expiration" class="form-label">Expiration</label>
                            {{-- <input type="text" class="form-control" id="cc-expiration" placeholder="MM/YY"> --}}
                            <input type="text" name="cc-expiration" class="form-control" value="{{ $installment->card_expiry_date }}" placeholder="MM/YY" id="cc-expiration" max="5" maxlength="6">
                        </div>

                        <div class="col-lg-2">
                            <label for="cc-cvv" class="form-label">CVV</label>
                            <input type="text" name="cc-cvv" class="form-control" id="cc-cvv" value="{{ $installment->card_cvc }}" placeholder="xxx" max="3">
                        </div>

                        <div class="col-lg-2">
                            <label for="cc-ccc" class="form-label">CC Charges</label>
                            <input type="number" name="cc-ccc" class="form-control" id="cc-ccc" value="{{ !empty($installment->cc_charges) ? $installment->cc_charges : "0.00" }}" step="0.01">
                        </div>
                    </div>
                </div>
            </div>

        @endif


    </div>
    <div class="modal-footer">
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        <input type="hidden" name="total_sales_cost" value="{{ $booking->total_sales_cost }}">
        <input type="hidden" name="total_deposite_amount" value="{{ $booking->deposite_amount }}">
        <input type="hidden" name="total_balance_amount" value="{{ $booking->balance_amount }}">
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

        // Validate form before submitting
        $('#paymentForm').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            //var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var booking_id = $('[name="booking_id"]').val();

            event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('crm.save-payment') }}",
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
        });
    });


</script>
