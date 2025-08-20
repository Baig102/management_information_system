
<form id="inquiryAssignmentForm">
    <?php echo csrf_field(); ?>
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Assign Inquiry | <?php echo e($inquiry->company_name.' '.$inquiry->source.' Inquiry'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">

         <?php if($inquiry->inquiry_assignment_status === 2): ?>
            <div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show material-shadow"
                role="alert">
                <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - This inquiry already assigned to some other user...
            </div>
        <?php else: ?>

            <div class="row mb-3">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Select Agent</label>
                    <div class="input-group">
                        <select class="form-control ms select2" name="agent_id" id="agent_id" data-placeholder="Select Agent">
                            <option></option>
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <option value="">No Agent</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label>Comments</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class=" ri-chat-1-line fs-5"></i></span>
                        <input type="text" class="form-control comments" id="comments" name="comments" value="" placeholder="Enter comments">
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
    <div class="modal-footer">
        <input type="hidden" name="inquiry_id" value="<?php echo e($inquiry->id); ?>">
        <button type="submit" class="btn btn-primary btn-sm" id="saveInquiryAssignment"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
        <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    </div>
</form>


<script>

    $(document).ready(function() {
        // $('.select2').select2({
        //     dropdownParent: $("#extraLargeModal")
        // });
        // Initialize select2 on the child modal shown event
        $('#extraLargeModal').on('shown.bs.modal', function () {
            $('.select2').select2({
                dropdownParent: $('#extraLargeModal')
            });
        });
        // Validate form before submitting
        $('#inquiryAssignmentForm').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            //var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var inquiry_id = $('[name="inquiry_id"]').val();

            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: "<?php echo e(route('crm.save-assign-inquiry')); ?>",
                data: $(this).serialize(),
                success: function(response) {
                    //console.log(response);
                    $('#inquiryRow_'+inquiry_id).remove();
                    $('.modal').modal('hide');
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
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/inquiry/modals/inquiry-assignment.blade.php ENDPATH**/ ?>