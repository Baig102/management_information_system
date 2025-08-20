<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add PNR</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="addPnr">
    <div class="modal-body" id="">

        @csrf
        <div class="row clearfix mb-3" id="edit_pnr_row">

            <div class="col-lg-8 mb-3">
                <label for="pnr" class="form-label">PNR<span class="text-danger"> *</span></label>
                <textarea name="pnr" id="pnr" class="form-control" rows="5"></textarea>
            </div>
            <div class="col-lg-4">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="flight_supplier" id="pnrFlightSupplier" name="supplier" data-placeholder="select flight supplier" required></select>
            </div>
        </div>
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
            initializeSelect2WithAjax('#pnrFlightSupplier', '{{ route('crm.get-vendors') }}', 'Search for Vendor', '#extraLargeModal');
        });

    });

      // Validate form before submitting
    $('#addPnr').submit(function(event) {

        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{ route('crm.save-pnr') }}",
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

                Swal.fire({
                    title: xhr.responseJSON.title,
                    icon: xhr.responseJSON.icon,
                    text: xhr.responseJSON.message,
                });
            }
        });
        return;
    });
</script>
