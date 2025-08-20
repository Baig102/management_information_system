<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Update PNR</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updatePnr">
    <div class="modal-body" id="">
        {{-- <pre>
            {{ print_r($booking) }}
        </pre> --}}
        @csrf
        <div class="row clearfix mb-3" id="edit_pnr_row">

            <div class="col-lg-12 mb-3">
                <textarea name="flight_pnr" id="edit_flight_pnr" class="">{!! $booking->flight_pnr !!}</textarea>
            </div>

        </div>
    </div>


<div class="modal-footer">

    <input type="hidden" name="booking_id" id="booking_id" value="{{ $booking->id }}">
    <button type="submit" class="btn btn-primary" id="updatePnr"><i class="ri-save-3-line me-1 align-middle"></i>Update</button>
    <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
</form>

<script>

var ckClassicEditor = document.querySelectorAll("#edit_flight_pnr")
if (ckClassicEditor) {
    Array.from(ckClassicEditor).forEach(function () {
        ClassicEditor
            .create(document.querySelector('#edit_flight_pnr'))
            .then(function (editor) {
                editor.ui.view.editable.element.style.height = '200px';
            })
            .catch(function (error) {
                console.error(error);
            });
    });
}



      // Validate form before submitting
    $('#updatePnr').submit(function(event) {

        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{ route('crm.update-pnr') }}",
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
