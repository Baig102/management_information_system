<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Update Booking Comments</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateComments">
    <div class="modal-body" id="">
        
        <?php echo csrf_field(); ?>
        <div class="row clearfix mb-3" id="edit_pnr_row">

            <div class="col-lg-12 mb-3">
                <textarea name="comments" id="booking_comments" class="form-control" rows="5"><?php echo e($booking->comments); ?></textarea>
            </div>

        </div>
    </div>


<div class="modal-footer">

    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo e($booking->id); ?>">
    <button type="submit" class="btn btn-primary" id="updateComments"><i class="ri-save-3-line me-1 align-middle"></i>Update</button>
    <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
</form>

<script>


      // Validate form before submitting
    $('#updateComments').submit(function(event) {

        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('crm.update-booking-comments')); ?>",
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
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/edit-booking-comments.blade.php ENDPATH**/ ?>