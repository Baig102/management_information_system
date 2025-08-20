<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Reject Booking</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="rejectBooking">
    <div class="modal-body" id="">

        @csrf
        <div class="row clearfix mb-3" id="">

            <div class="col-lg-12 mb-3">
                <label for="comments" class="form-label">Reasion of rejection<span class="text-danger"> *</span></label>
                <textarea name="comments" id="comments" class="form-control" rows="5"></textarea>
            </div>

        </div>
    </div>


<div class="modal-footer">

    <input type="hidden" name="booking_id" id="booking_id" value="{{ $booking->id }}">
    <button type="submit" class="btn btn-primary"><i class="ri-save-3-line me-1 align-middle"></i>Reject</button>
    <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
</form>

<script>



    $('#rejectBooking').submit(function(event) {
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure to reject this booking!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    type: 'POST',
                    url: "{{ route('crm.reject-booking') }}",
                    data: $(this).serialize(),
                    success: function(response) {
                        //console.log(response);
                        $('.modal.extraLargeModal').modal('toggle');
                        $('.approveReject_'+ booking_id).prop('disabled', true);

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
            }
        })
        return;
    });
</script>
