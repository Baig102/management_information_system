<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add / Update Visa Actual Net</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateVisaActualNet">
    <div class="modal-body" id="visa_details">

        <?php echo csrf_field(); ?>
        <?php $__currentLoopData = $booking->visas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visa_key => $visa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($visa_key > 0): ?>
            <hr>
        <?php endif; ?>
        <div class="row clearfix visa_info mb-3" id="visaInfo_<?php echo e($visa->id); ?>">
            <div class="col-lg-2 col-md-6">
                <label>Visa Category<span class="text-danger"> *</span></label>
                <select class="visa_category select2" id="visaCategory_<?php echo e($visa->id); ?>" name="visa[<?php echo e($visa->id); ?>][visa_category]" data-placeholder="select visa visa category" required>
                    <option value="<?php echo e($visa->visa_category); ?>"> <?php echo e($visa->visa_category); ?> </option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label>Visa Country<span class="text-danger"> *</span></label>
                <select class="visa_country select2" id="visaCountry_<?php echo e($visa->id); ?>" name="visa[<?php echo e($visa->id); ?>][visa_country]" data-placeholder="select visa visa country" required>
                    <option value=" <?php echo e($visa->visa_country); ?>"> <?php echo e($visa->visa_country); ?> </option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Number of Pax<span class="text-danger"> *</span></label>
                <input type="number" class="form-control no_of_pax" id="noOfPax_<?php echo e($visa->id); ?>" name="visa[<?php echo e($visa->id); ?>][no_of_pax]" value="<?php echo e($visa->no_of_pax); ?>" placeholder="No Of Pax" readonly>
            </div>
            <div class="col-lg-2">
                <label for="nationality_<?php echo e($visa->id); ?>" class="form-label">Nationality</label>
                <input type="text" name="visa[<?php echo e($visa->id); ?>][nationality]" value="<?php echo e($visa->nationality); ?>" class="form-control" placeholder="Nationality" id="nationality_<?php echo e($visa->id); ?>" autocomplete="off" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Sale Cost</label>
                <input type="number" class="form-control visa_sale_cost" name="visa[<?php echo e($visa->id); ?>][sale_cost]" value="<?php echo e($visa->sale_cost); ?>" placeholder="Sale Cost" step="0.01" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control visa_net_cost" name="visa[<?php echo e($visa->id); ?>][net_cost]" value="<?php echo e($visa->net_cost); ?>" placeholder="Net Cost" step="0.01" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Actual Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control transport_actual_net_cost" name="visa[<?php echo e($visa->id); ?>][actual_net_cost]" value="<?php echo e($visa->actual_net_cost); ?>" placeholder="Net Cost" step="0.01" required>
            </div>
            <div class="col-lg-2 col-md-6">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="visa_supplier select2" id="visaSupplier_<?php echo e($visa->id); ?>" name="visa[<?php echo e($visa->id); ?>][supplier]" data-placeholder="select visa supplier" required>
                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v_key => $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($vendor->name); ?>" <?php echo e(($vendor->name == $visa->supplier) ? "selected" : ""); ?>> <?php echo e($vendor->name); ?> </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Remarks<span class="text-danger"> *</span></label>
                <input type="text" class="form-control remarks" name="visa[<?php echo e($visa->id); ?>][remarks]" value="<?php echo e($visa->remarks); ?>" placeholder="Remarks" readonly>
            </div>
            <input type="hidden" name="visa[<?php echo e($visa->id); ?>][id]" value="<?php echo e($visa->id); ?>">

        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>


    <div class="modal-footer">
        <input type="hidden" name="booking_id" id="booking_id" value="<?php echo e($booking->id); ?>">
        <button type="submit" class="btn btn-primary" id="updatePassenger"><i class="ri-save-3-line me-1 align-middle"></i>Update</button>
        <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    </div>
</form>

<script>
    $(function() {

        $('#extraLargeModal').on('shown.bs.modal', function() {
            //console.log('ok load');
            $('.select2').select2({
                dropdownParent: $('#extraLargeModal')
            });
            // initializeSelect2WithAjax('.airports', '<?php echo e(route('crm.get-airports')); ?>', 'Search for airports', '.modal');
        });

    })
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });


    // Validate form before submitting
    $('#updateVisaActualNet').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('crm.update-visa-actual-net')); ?>",
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
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/add-visa-actual-net.blade.php ENDPATH**/ ?>