<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add / Update Visa Actual Net</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateVisaActualNet">
    <div class="modal-body" id="visa_details">

        @csrf
        @foreach ($booking->visas as $visa_key => $visa)
        @if ($visa_key > 0)
            <hr>
        @endif
        <div class="row clearfix visa_info mb-3" id="visaInfo_{{ $visa->id }}">
            <div class="col-lg-2 col-md-6">
                <label>Visa Category<span class="text-danger"> *</span></label>
                <select class="visa_category select2" id="visaCategory_{{ $visa->id }}" name="visa[{{ $visa->id }}][visa_category]" data-placeholder="select visa visa category" required>
                    <option value="{{$visa->visa_category}}"> {{$visa->visa_category}} </option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label>Visa Country<span class="text-danger"> *</span></label>
                <select class="visa_country select2" id="visaCountry_{{ $visa->id }}" name="visa[{{ $visa->id }}][visa_country]" data-placeholder="select visa visa country" required>
                    <option value=" {{$visa->visa_country}}"> {{$visa->visa_country}} </option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Number of Pax<span class="text-danger"> *</span></label>
                <input type="number" class="form-control no_of_pax" id="noOfPax_{{ $visa->id }}" name="visa[{{ $visa->id }}][no_of_pax]" value="{{ $visa->no_of_pax }}" placeholder="No Of Pax" readonly>
            </div>
            <div class="col-lg-2">
                <label for="nationality_{{ $visa->id }}" class="form-label">Nationality</label>
                <input type="text" name="visa[{{ $visa->id }}][nationality]" value="{{ $visa->nationality }}" class="form-control" placeholder="Nationality" id="nationality_{{ $visa->id }}" autocomplete="off" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Sale Cost</label>
                <input type="number" class="form-control visa_sale_cost" name="visa[{{ $visa->id }}][sale_cost]" value="{{ $visa->sale_cost }}" placeholder="Sale Cost" step="0.01" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control visa_net_cost" name="visa[{{ $visa->id }}][net_cost]" value="{{ $visa->net_cost }}" placeholder="Net Cost" step="0.01" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Actual Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control transport_actual_net_cost" name="visa[{{ $visa->id }}][actual_net_cost]" value="{{ $visa->actual_net_cost }}" placeholder="Net Cost" step="0.01" required>
            </div>
            <div class="col-lg-2 col-md-6">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="visa_supplier select2" id="visaSupplier_{{ $visa->id }}" name="visa[{{ $visa->id }}][supplier]" data-placeholder="select visa supplier" required>
                    @foreach ($vendors as $v_key => $vendor)
                        <option value="{{ $vendor->name }}" {{ ($vendor->name == $visa->supplier) ? "selected" : "" }}> {{$vendor->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Remarks<span class="text-danger"> *</span></label>
                <input type="text" class="form-control remarks" name="visa[{{ $visa->id }}][remarks]" value="{{ $visa->remarks }}" placeholder="Remarks" readonly>
            </div>
            <input type="hidden" name="visa[{{ $visa->id }}][id]" value="{{ $visa->id }}">

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
            // initializeSelect2WithAjax('.airports', '{{ route('crm.get-airports') }}', 'Search for airports', '.modal');
        });

    })
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });


    // Validate form before submitting
    $('#updateVisaActualNet').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{ route('crm.update-visa-actual-net') }}",
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
                    title: 'Error',
                    icon: 'error',
                    text: xhr.responseJSON.message,
                });
            }
        });
        return;
    });
</script>
