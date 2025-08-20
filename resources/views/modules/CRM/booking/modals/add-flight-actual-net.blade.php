<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add / Update Flight Actual Net</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateFlightActualNet">
    <div class="modal-body" id="">
        {{-- <pre>
            {{ print_r($booking) }}
        </pre> --}}
        @csrf
        @foreach ($booking->pnrs as $pnr)
        <div class="row clearfix mb-3" id="pnrInfo_{{ $pnr->id }}">
            <div class="col-lg-12 mb-3">
                <label for="pnr" class="form-label">PNR<span class="text-danger"> *</span></label>
                <textarea name="pnr" id="pnr" class="form-control" rows="5" readonly>{{ $pnr->pnr }}</textarea>
            </div>
            <div class="col-lg-3">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="flight_supplier form-control" id="pnrFlightSupplier{{ $pnr->id }}" name="flight[{{ $pnr->id }}][supplier]" data-placeholder="select transport supplier" required>
                    @foreach ($vendors as $v_key => $vendor)
                    <option value="{{ $vendor->name }}" {{ ($vendor->name == $pnr->supplier) ? "selected" : "" }}> {{$vendor->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label>Actual Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control transport_actual_net_cost select2" name="flight[{{ $pnr->id }}][actual_net_cost]" value="{{ $pnr->actual_net_cost }}" placeholder="Net Cost" step="0.01" required>
            </div>
            <div class="col-lg-3">
                <label>Aviation Fee Supplier<span class="text-danger d-none"> *</span></label>
                <select class="flight_supplier form-control" id="AviationFeeSupplier{{ $pnr->id }}" name="flight[{{ $pnr->id }}][aviation_fee_supplier]" data-placeholder="select transport supplier">
                    <option value="">Select Aviation Fee Supplier</option>
                    @foreach ($vendors as $v_key => $vendor)
                    <option value="{{ $vendor->name }}" {{ ($vendor->name == $pnr->aviation_fee_supplier) ? "selected" : "" }}> {{$vendor->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label>Aviation Fee</label>
                <input type="number" class="form-control aviation_fee select2" name="flight[{{ $pnr->id }}][aviation_fee]" value="{{ $pnr->aviation_fee }}" placeholder="Aviation Fee" step="0.01" id="aviationFeeInput{{ $pnr->id }}">
            </div>
        </div>
        <input type="hidden" name="flight[{{ $pnr->id }}][id]" value="{{ $pnr->id }}">
        @if (!$loop->last)
            <hr>
        @endif
        @endforeach
    </div>


<div class="modal-footer">

    <input type="hidden" name="booking_id" id="booking_id" value="{{ $booking->id }}">
    <button type="submit" class="btn btn-primary" id="addPnr"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
    <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
</form>

<script>
    $(document).ready(function() {

        // Initialize Select2 when the modal is shown
        $('#extraLargeModal').on('shown.bs.modal', function() {
            console.log('Modal shown, initializing Select2');
            // initializeSelect2WithAjax('#pnrFlightSupplier', '{{ route('crm.get-vendors') }}', 'Search for Vendor', '#extraLargeModal');
            $('.select2').select2();
        });

        $('#aviationFeeInput{{ $pnr->id }}').on('input', function() {
            var fee = parseFloat($(this).val());
            var supplierSelect = $('#AviationFeeSupplier{{ $pnr->id }}');
            if (fee > 0) {
                supplierSelect.prop('required', true);
            } else {
                supplierSelect.prop('required', false);
            }
        });
    });
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });


    // Validate form before submitting
    $('#updateFlightActualNet').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{ route('crm.update-flight-actual-net') }}",
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
