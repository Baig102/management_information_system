<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add / Update Flight Actual Net</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateFlightActualNet">
    <div class="modal-body" id="">
        
        <?php echo csrf_field(); ?>
        <?php $__currentLoopData = $booking->pnrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pnr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="row clearfix mb-3" id="pnrInfo_<?php echo e($pnr->id); ?>">
            <div class="col-lg-12 mb-3">
                <label for="pnr" class="form-label">PNR<span class="text-danger"> *</span></label>
                <textarea name="pnr" id="pnr" class="form-control" rows="5" readonly><?php echo e($pnr->pnr); ?></textarea>
            </div>
            <div class="col-lg-3">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="flight_supplier form-control" id="pnrFlightSupplier<?php echo e($pnr->id); ?>" name="flight[<?php echo e($pnr->id); ?>][supplier]" data-placeholder="select transport supplier" required>
                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v_key => $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($vendor->name); ?>" <?php echo e(($vendor->name == $pnr->supplier) ? "selected" : ""); ?>> <?php echo e($vendor->name); ?> </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-lg-3">
                <label>Actual Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control transport_actual_net_cost select2" name="flight[<?php echo e($pnr->id); ?>][actual_net_cost]" value="<?php echo e($pnr->actual_net_cost); ?>" placeholder="Net Cost" step="0.01" required>
            </div>
            <div class="col-lg-3">
                <label>Aviation Fee Supplier<span class="text-danger d-none"> *</span></label>
                <select class="flight_supplier form-control" id="AviationFeeSupplier<?php echo e($pnr->id); ?>" name="flight[<?php echo e($pnr->id); ?>][aviation_fee_supplier]" data-placeholder="select transport supplier">
                    <option value="">Select Aviation Fee Supplier</option>
                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v_key => $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($vendor->name); ?>" <?php echo e(($vendor->name == $pnr->aviation_fee_supplier) ? "selected" : ""); ?>> <?php echo e($vendor->name); ?> </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-lg-3">
                <label>Aviation Fee</label>
                <input type="number" class="form-control aviation_fee select2" name="flight[<?php echo e($pnr->id); ?>][aviation_fee]" value="<?php echo e($pnr->aviation_fee); ?>" placeholder="Aviation Fee" step="0.01" id="aviationFeeInput<?php echo e($pnr->id); ?>">
            </div>
        </div>
        <input type="hidden" name="flight[<?php echo e($pnr->id); ?>][id]" value="<?php echo e($pnr->id); ?>">
        <?php if(!$loop->last): ?>
            <hr>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>


<div class="modal-footer">

    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo e($booking->id); ?>">
    <button type="submit" class="btn btn-primary" id="addPnr"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
    <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
</form>

<script>
    $(document).ready(function() {

        // Initialize Select2 when the modal is shown
        $('#extraLargeModal').on('shown.bs.modal', function() {
            console.log('Modal shown, initializing Select2');
            // initializeSelect2WithAjax('#pnrFlightSupplier', '<?php echo e(route('crm.get-vendors')); ?>', 'Search for Vendor', '#extraLargeModal');
            $('.select2').select2();
        });

        $('#aviationFeeInput<?php echo e($pnr->id); ?>').on('input', function() {
            var fee = parseFloat($(this).val());
            var supplierSelect = $('#AviationFeeSupplier<?php echo e($pnr->id); ?>');
            if (fee > 0) {
                supplierSelect.prop('required', true);
            } else {
                supplierSelect.prop('required', false);
            }
        });
    });
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });


    // Validate form before submitting
    $('#updateFlightActualNet').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('crm.update-flight-actual-net')); ?>",
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
                    title: 'Error',
                    icon: 'error',
                    text: xhr.responseJSON.message,
                });
            }
        });
        return;
    });
</script>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/add-flight-actual-net.blade.php ENDPATH**/ ?>