<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Change Booking Ownership</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="rejectBooking">
    <div class="modal-body" id="">

        <?php echo csrf_field(); ?>

        <div class="row clearfix mb-3" id="">

            <div class="col-lg-4 col-md-6 mb-3">
                <label>New Owner</label>
                <select class="select2 form-control form-control-sm" name="created_by" required="" data-placeholder="select">
                    <option></option>
                    <?php $__currentLoopData = $activeUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->id); ?>" <?php echo e(($user->id == $booking->created_by) ? "selected" : ""); ?>><?php echo e($user->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

        </div>

        <div class="row clearfix mb-3" id="">

            <div class="col-lg-12 mb-3">
                <label for="comments" class="form-label">Reasion of ownership change?<span class="text-danger"> *</span></label>
                <textarea name="comments" id="comments" class="form-control" rows="5" required></textarea>
            </div>

        </div>
    </div>


<div class="modal-footer">

    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo e($booking->id); ?>">
    <button type="submit" class="btn btn-primary"><i class="ri-save-3-line me-1 align-middle"></i>Change OwnerShip</button>
    <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
</form>

<script>

    $(document).ready(function() {
        // Initialize Select2 when the modal is shown
        $('#extraLargeModal').on('shown.bs.modal', function() {
            // initializeSelect2WithAjax('#pnrFlightSupplier', '<?php echo e(route('crm.get-vendors')); ?>', 'Search for Vendor', '#extraLargeModal');
            $('.select2').select2();
        });
    });

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });


    $('#rejectBooking').submit(function(event) {
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure to change ownership of this booking!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('crm.change-booking-ownership')); ?>",
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
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/change-booking-ownership.blade.php ENDPATH**/ ?>