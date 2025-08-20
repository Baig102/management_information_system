
<form id="inquiryAssignmentActionForm">
    <?php echo csrf_field(); ?>
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Add Inquiry Communication</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">

         <?php if($inquiry->inquiry_assignment_status === 1 || $inquiry->inquiry_assigned_to != Auth::id()): ?>
            <div class="alert alert-warning alert-dismissible bg-warning text-white alert-label-icon fade show material-shadow"
                role="alert">
                <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - This inquiry is not assigned or not assigned to you...
            </div>
        <?php else: ?>

            <div class="row mb-3">
                <div class="col-lg-6 col-md-12 mb-3">
                    <label>Select Action</label>
                    <div class="input-group">
                        <select class="form-control ms select2" name="inquiry_status" id="inquiry_status" data-placeholder="Select Agent">
                            <option></option>
                            <option value="Follow up">Follow up</option>
                            <option value="Pending">Pending</option>
                            <option value="Booked with us">Booked with us</option>
                            <option value="Booked with other Comapny">Booked with other Comapny</option>
                            <option value="Not Interesting">Not Interesting</option>
                            <option value="Just Looking">Just Looking</option>
                            <option value="Call not Attending">Call not Attending</option>
                            <option value="On Voice Mail">On Voice Mail</option>
                            <option value="Wrong Number">Wrong Number</option>
                            <option value="Fake">Fake</option>
                            <option value="Added On Whatsapp">Added On Whatsapp</option>
                            <option value="Payment Done">Payment Done</option>
                            <option value="No Response">No Response</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 mb-3">
                    <label>Comments</label>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class=" ri-chat-1-line fs-5"></i></span>
                        <input type="text" class="form-control comments" id="comments" name="comments" value="" placeholder="Enter comments" required>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
    <div class="modal-footer">

        <input type="hidden" name="inquiry_id" value="<?php echo e($inquiry->id); ?>">
        <input type="hidden" name="inquiries_assignment_id" value="<?php echo e($inquiry->inquiryAssigments->id); ?>">
        <button type="submit" class="btn btn-primary btn-sm" id="saveInquiryAssignment"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
        <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    </div>
</form>


<script>

    $(document).ready(function() {
        // $('.select2').select2({
        //     dropdownParent: $("#extraLargeModal")
        // });

        // Validate form before submitting
        $('#inquiryAssignmentActionForm').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            //var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var inquiry_id = $('[name="inquiry_id"]').val();

            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: "<?php echo e(route('crm.save-inquiry-assign-action')); ?>",
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);

                    //if (response.code == 200) {
                        $('.modal.largeModal').modal('toggle');
                        $('.modal.fullscreeexampleModal').modal('toggle');
                        view_inquiry(inquiry_id);
                    //}

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
    });


</script>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/inquiry/modals/view-inquiry-action.blade.php ENDPATH**/ ?>