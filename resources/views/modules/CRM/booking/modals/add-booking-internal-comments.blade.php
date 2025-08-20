<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add Booking Comments</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="addInternalComments">
    <div class="modal-body" id="">

        @csrf
        <div class="row mb-3">
            <div class="col-lg-3">
                <label for="comments_type" class="form-label">Comments Type</label>
                <select name="title" id="comments_type" class="form-select select2 form-select-lg">
                    <option value="general">General</option>
                    <option value="close traveling">Close Traveling</option>
                </select>
            </div>
        </div>
        <div class="row clearfix mb-3" id="edit_pnr_row">

            <div class="col-lg-12 mb-3">
                <label for="booking_comments" class="form-label">Comments <span class="text-danger">*</span></label>
                <textarea name="comments" id="booking_comments" class="form-control" rows="5"></textarea>
            </div>

        </div>
    </div>


<div class="modal-footer">

    <input type="hidden" name="booking_id" id="booking_id" value="{{ $booking->id }}">
    <button type="submit" class="btn btn-primary" id="addInternalComments"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
    <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
</form>

<script>


      // Validate form before submitting
    $('#addInternalComments').submit(function(event) {

        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{ route('crm.save-booking-internal-comments') }}",
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
                    //title: response.title,
                    //icon: response.icon,
                    text: xhr.responseJSON.message,
                });
            }
        });
        return;
    });
</script>
