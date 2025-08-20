{{-- <form action="{{ route('crm.save-installment-plan') }}" method="post" id="installmentForm" enctype="multipart/form-data"> --}}
<form id="installmentForm">
    @csrf
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Add Installment Plan | Booking Number:
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
                    <td><span class="text-primary">{{ $booking->currency . ' ' . number_format($booking->total_sales_cost, 2) }}</span></td>
                    <td><span class="text-success">{{ $booking->currency . ' ' . number_format($booking->deposite_amount, 2) }}</span></td>
                    <td><span class="text-danger">{{ $booking->currency . ' ' . number_format($booking->balance_amount, 2) }}</span>
                        <input type="hidden" name="balance_amount" value="{{ $booking->balance_amount }}">
                    </td>
                    {{-- <td><span class="badge bg-primary-subtle text-primary">Backlog</span></td> --}}

                </tr>
            </tbody>
        </table>
        {{ $booking->total_installment }}
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
                        <th scope="row">
                            {{ $payment->installment_no == 0 ? 'Down Payment' : $payment->installment_no }}</th>
                        <td><span class="text-success">{{ $booking->currency . ' ' . number_format($payment->reciving_amount, 2) }}</span>
                        </td>
                        <td><span class="text-danger">{{ $booking->currency . ' ' . number_format($payment->remaining_amount, 2) }}</span>
                        </td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ $payment->deposit_date }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        <h6 class="fw-bold text-primary">Create Installment Plan</h6>
        @if ($booking->payment_type === 1)

            <!-- Warning Alert -->
            <div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show material-shadow"
                role="alert">
                <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - This booking does not support intallment plan...
            </div>
        @else
            @if ($booking->installmentPlan->count() <= 0)
                @if ($booking->total_installment <= 0)
                <div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show material-shadow"
                role="alert">
                    <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - Please update total number of intallments...
                </div>
                @else
                    @php $instalment_amount = $booking->balance_amount / $booking->total_installment; @endphp
                    @for ($i = 1; $i <= $booking->total_installment; $i++)
                        <div class="row clearfix hotel_info mb-1" id="installment_plan">

                            <div class="col-lg-4 col-md-6">
                                <label>Installment Number</label>
                                <input type="text" class="form-control"
                                    name="installment[{{ $i }}][installment_number]"
                                    value="{{ $i }}" placeholder="Installment Number" readonly>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <label>Installment Due Date</label>
                                <input type="text" class="form-control flatpickr due_date flatpickr-date"
                                    data-provider="flatpickr" autocomplete="off" id="dueDate_{{ $i }}"
                                    name="installment[{{ $i }}][due_date]" value=""
                                    placeholder="Installment Due Date" required>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <label>Installment Amount</label>
                                <input type="number" class="form-control installment_amount"
                                    name="installment[{{ $i }}][amount]" value="{{ round($instalment_amount,2) }}"
                                    placeholder="Installment Amount" step="0.01" required="">
                            </div>

                        </div>
                    @endfor
                @endif

            @endif
        @endif


    </div>

    <div class="modal-footer">
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
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

    $(document).ready(function() {
        // Validate form before submitting
        $('#installmentForm').submit(function(event) {

            // Perform custom validation here

            var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var booking_id = $('[name="booking_id"]').val();

            // Calculate total installment amount
            var totalInstallmentAmount = 0;
            $('.installment_amount').each(function() {
                totalInstallmentAmount += parseFloat($(this).val());
            });
            console.log(totalInstallmentAmount);
            //console.log(totalInstallmentAmount);
            // Validate total installment amount
            if (Math.round(totalInstallmentAmount) !== balance_amount) {
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
                    url: "{{ route('crm.save-installment-plan') }}",
                    data: $(this).serialize(),
                    success: function(response) {

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
