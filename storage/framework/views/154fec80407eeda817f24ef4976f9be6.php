
<form id="bulkInquiryAssignmentForm">
    <?php echo csrf_field(); ?>
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Assign Bulk Inquiries </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">


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

    </div>
    <div class="modal-footer">
        <input type="hidden" name="inquiry_ids" value="<?php echo e($inquiry_ids); ?>">
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
        $('#bulkInquiryAssignmentForm').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            //var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var inquiry_ids = $('[name="inquiry_ids"]').val();

            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: "<?php echo e(route('crm.save-bulk-assign-inquiry')); ?>",
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);

                   // Assuming response.inquiry_ids contains the IDs of successfully assigned inquiries
                    if (response.inquiry_ids && response.inquiry_ids.length) {
                        response.inquiry_ids.forEach(function(inquiry_id) {
                            // Use DataTable API to remove rows
                            var row = $('#alternative-pagination').DataTable().row('#inquiryRow_' + inquiry_id);

                            // Check if the row exists and then remove it
                            if (row.any()) { // Change this line
                                row.remove().draw(); // Remove the row and redraw the table
                            }
                        });
                    }
                    $('#selectedInquiries').val('');
                    $('.modal').modal('hide');
                    /* Swal.fire({
                        title: response.title,
                        icon: response.icon,
                        text: response.message,
                    }); */
                    Swal.fire({
                        title: response.title,
                        icon: response.icon,
                        text: response.message,
                        // showCancelButton: false,
                        // confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.log(xhr, status, error);
                    // Show error message
                    //alert(xhr.responseJSON.message);

                    Swal.fire({
                        title: xhr.responseJSON.title,
                        icon: xhr.responseJSON.icon,
                        text: xhr.responseJSON.message,
                    });
                }
            });
            return;
        });
    });


</script>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/inquiry/modals/inquiry-bulk-assignment.blade.php ENDPATH**/ ?>