<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add / Update Hotel Actual Net</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateTransportDetails">
    <div class="modal-body" id="transport_details">
        {{-- <pre>
            {{ print_r($booking) }}
        {{ print_r($vendors) }}
        </pre> --}}
        @php
            if ($booking->is_full_package == 0) {
                $filed_condition_sales_cost = 'required';
                $required_check_sales_cost = '<span class="text-danger"> *</span>';
            } else {
                $filed_condition_sales_cost = 'readonly';
                $required_check_sales_cost = '';
            }
        @endphp
        @csrf
        @foreach ($booking->transports as $pass_key => $transport)
        @if ($pass_key > 0)
            <hr>
        @endif
        <div class="row clearfix transport_info mb-3" id="transportInfo_{{ $transport->id }}">
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Transport Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transport_type{{ $transport->id }}" name="transport[{{ $transport->id }}][transport_type]" value="{{ $transport->transport_type }}" placeholder="airport" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Airport<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transportAirport_{{ $transport->id }}" name="transport[{{ $transport->id }}][airport]" value="{{ $transport->airport }}" placeholder="airport" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Location<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transportLocation{{ $transport->id }}" name="transport[{{ $transport->id }}][location]" value="{{ $transport->location }}" placeholder="Location" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Date<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transport_date_{{ $transport->id }}" name="transport[{{ $transport->id }}][transport_date]" value="{{ $transport->transport_date }}" placeholder="Date" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Time<span class="text-danger"> *</span></label>
                <input type="time" class="form-control time" name="transport[{{ $transport->id }}][time]" value="{{ $transport->time }}" placeholder="Time" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Car Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transportCarType{{ $transport->id }}" name="transport[{{ $transport->id }}][car_type]" value="{{ $transport->car_type }}" placeholder="Car Type" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Sale Cost{!! $required_check_sales_cost !!}</label>
                <input type="number" class="form-control transport_sale_cost" name="transport[{{ $transport->id }}][sale_cost]" value="{{ $transport->sale_cost }}" placeholder="Sale Cost" step="0.01" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control transport_net_cost" name="transport[{{ $transport->id }}][net_cost]" value="{{ $transport->net_cost }}" placeholder="Net Cost" step="0.01" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Actual Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control transport_actual_net_cost" name="transport[{{ $transport->id }}][actual_net_cost]" value="{{ $transport->actual_net_cost }}" placeholder="Net Cost" step="0.01" required>
            </div>

            <div class="col-lg-2 col-md-6">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="transport_supplier form-control select2" id="transportSupplier_{{ $transport->id }}" name="transport[{{ $transport->id }}][supplier]" data-placeholder="select transport supplier" required>
                    @foreach ($vendors as $v_key => $vendor)
                    <option value="{{ $vendor->name }}" {{ ($vendor->name == $transport->supplier) ? "selected" : "" }}> {{$vendor->name}} </option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="transport[{{ $transport->id }}][id]" value="{{ $transport->id }}">

        </div>
        @endforeach
    </div>


    <div class="modal-footer">
        <input type="hidden" name="booking_id" id="booking_id" value="{{ $booking->id }}">
        <button type="submit" class="btn btn-primary" id="updatePassenger"><i class="ri-save-3-line me-1 align-middle"></i>Update</button>
        <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    </div>
</form>

<script>
    $(function() {

        $('#extraLargeModal').on('shown.bs.modal', function() {
            //console.log('ok load');
            $('.select2').select2({
                dropdownParent: $('#extraLargeModal')
            });
            initializeSelect2WithAjax('.airports', '{{ route('crm.get-airports') }}', 'Search for airports', '.modal');
        });

    })
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });


    // Validate form before submitting
    $('#updateTransportDetails').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{ route('crm.update-transport-actual-net') }}",
            data: $(this).serialize(),
            success: function(response) {
                console.log(response);

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
                    title: 'Error',
                    icon: 'error',
                    text: xhr.responseJSON.message,
                });
            }
        });
        return;
    });
</script>
