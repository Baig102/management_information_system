{{-- <form action="{{ route('crm.save-installment-plan') }}" method="post" id="otherChargesForm" enctype="multipart/form-data"> --}}
<form id="otherChargesForm">
    @csrf
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Add Other Charges | Booking Number:
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

        <h6 class="fw-bold text-primary">Add Other Charges</h6>
        <div class="row mb-3">
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Charges Type</label>

                <div class="input-group">
                    <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                    <select class="form-control form-control-sm" name="charges_type" required="" data-placeholder="select charges type">
                        <option value="Date Change">Date Change</option>
                        <option value="Admin Charges">Admin Charges</option>
                        <option value="Installment Charges">Installment Charges</option>
                    </select>
                </div>
                <label><small class="text-danger"> Don't Add CC Charges Here</small></label>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Amount</label>
                <div class="input-group">
                    <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                    <input type="number" class="form-control amount" id="amount" name="amount" value="" placeholder="Enter Amount" step="0.01" required>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Comments</label>
                <div class="input-group">
                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                    <input type="text" name="comments" value="" placeholder="Add Comments" class="form-control" id="comments" autocomplete="off">
                </div>
                <label class="text-danger"><small>add complete information into this comments</small></label>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Charges Date</label>
                <div class="input-group">
                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                    <input type="text" name="charges_at" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="charges_at" autocomplete="off">
                </div>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        <button type="submit" class="btn btn-primary btn-sm" id="saveOtherCharges"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
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


    $(document).ready(function() {
         $('.select2').select2();
        // Validate form before submitting
        $('#otherChargesForm').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            //var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var booking_id = $('[name="booking_id"]').val();

            event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('crm.save-other-charges') }}",
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
