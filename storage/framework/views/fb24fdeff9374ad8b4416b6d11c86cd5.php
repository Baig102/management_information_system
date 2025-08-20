
<form id="updateTicketStatus">
    <?php echo csrf_field(); ?>
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title">Update Invoice Status | Booking Number: <?php echo e($booking->booking_prefix . $booking->booking_number); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="row g-4 mb-3 d-none">

            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <label>Ticket Supplier</label>
                    <select class="form-control ticket_supplier" name="ticket_supplier">
                        <option value="">Select Ticket Supplier</option>
                        <?php $__currentLoopData = $flightSuppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket_vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ticket_vendor->id.'__'.$ticket_vendor->name); ?>" <?php echo e($booking->ticket_supplier_id == $ticket_vendor->id ? "selected" : ""); ?>><?php echo e($ticket_vendor->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>
       <div class="row g-4">
        
            <?php $__currentLoopData = $ticketStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-sm-6">
                <div class="form-check card-radio">
                    <input id="ticket_status_<?php echo e($key+1); ?>" name="ticket_status" type="radio" class="form-check-input" value="<?php echo e($status->detail_number); ?>" <?php echo e(($booking->ticket_status == $status->detail_number) ? "checked" : ""); ?>>
                    <label class="form-check-label" for="ticket_status_<?php echo e($key+1); ?>">
                        <span class="fs-16 text-muted me-2"><i class="ri-gradienter-line align-bottom"></i></span>
                        
                        <span class="fs-14 text-wrap"><?php echo e($status->details); ?></span>
                    </label>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
       </div>

    </div>
    <div class="modal-footer">
        <input type="hidden" name="booking_id" value="<?php echo e($booking->id); ?>">
        <button type="submit" class="btn btn-primary btn-sm" id="saveTicketStatus"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
        <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    </div>
</form>


<script>



    // $("#ticket_supplier").select2({
    //     dropdownParent: $('#extraLargeModal .modal-content')
    // });
    // $('#ticket_supplier').select2('destroy');
    //initializeSelect2WithAjax('#updateTicketStatus #ticket_supplier', '<?php echo e(route('ams.get-vendors','Tickets')); ?>', 'Search for Ticket Vendor');

    $(function(){
        $('#extraLargeModal').on('shown.bs.modal', function () {
            $('.ticket_supplier').select2({
                dropdownParent: $('#extraLargeModal')
            });
        });
    })

    // Validate form before submitting
    $('#updateTicketStatus').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('crm.save-ticket-status')); ?>",
            data: $(this).serialize(),
            success: function(response) {
                console.log(response);

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
                    //title: response.title,
                    //icon: response.icon,
                    text: xhr.responseJSON.message,
                });
            }
        });
        return;
    });


</script>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/update-ticket-status.blade.php ENDPATH**/ ?>