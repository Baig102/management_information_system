<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add / Update Hotel Actual Net</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateTransportDetails">
    <div class="modal-body" id="transport_details">
        
        <?php
            if ($booking->is_full_package == 0) {
                $filed_condition_sales_cost = 'required';
                $required_check_sales_cost = '<span class="text-danger"> *</span>';
            } else {
                $filed_condition_sales_cost = 'readonly';
                $required_check_sales_cost = '';
            }
        ?>
        <?php echo csrf_field(); ?>
        <?php $__currentLoopData = $booking->transports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pass_key => $transport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($pass_key > 0): ?>
            <hr>
        <?php endif; ?>
        <div class="row clearfix transport_info mb-3" id="transportInfo_<?php echo e($transport->id); ?>">
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Transport Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transport_type<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][transport_type]" value="<?php echo e($transport->transport_type); ?>" placeholder="airport" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Airport<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transportAirport_<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][airport]" value="<?php echo e($transport->airport); ?>" placeholder="airport" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Location<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transportLocation<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][location]" value="<?php echo e($transport->location); ?>" placeholder="Location" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Date<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transport_date_<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][transport_date]" value="<?php echo e($transport->transport_date); ?>" placeholder="Date" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Time<span class="text-danger"> *</span></label>
                <input type="time" class="form-control time" name="transport[<?php echo e($transport->id); ?>][time]" value="<?php echo e($transport->time); ?>" placeholder="Time" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Car Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transportCarType<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][car_type]" value="<?php echo e($transport->car_type); ?>" placeholder="Car Type" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Sale Cost<?php echo $required_check_sales_cost; ?></label>
                <input type="number" class="form-control transport_sale_cost" name="transport[<?php echo e($transport->id); ?>][sale_cost]" value="<?php echo e($transport->sale_cost); ?>" placeholder="Sale Cost" step="0.01" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control transport_net_cost" name="transport[<?php echo e($transport->id); ?>][net_cost]" value="<?php echo e($transport->net_cost); ?>" placeholder="Net Cost" step="0.01" readonly>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Actual Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control transport_actual_net_cost" name="transport[<?php echo e($transport->id); ?>][actual_net_cost]" value="<?php echo e($transport->actual_net_cost); ?>" placeholder="Net Cost" step="0.01" required>
            </div>

            <div class="col-lg-2 col-md-6">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="transport_supplier form-control select2" id="transportSupplier_<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][supplier]" data-placeholder="select transport supplier" required>
                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v_key => $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($vendor->name); ?>" <?php echo e(($vendor->name == $transport->supplier) ? "selected" : ""); ?>> <?php echo e($vendor->name); ?> </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <input type="hidden" name="transport[<?php echo e($transport->id); ?>][id]" value="<?php echo e($transport->id); ?>">

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
            initializeSelect2WithAjax('.airports', '<?php echo e(route('crm.get-airports')); ?>', 'Search for airports', '.modal');
        });

    })
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });


    // Validate form before submitting
    $('#updateTransportDetails').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('crm.update-transport-actual-net')); ?>",
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
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/add-transport-actual-net.blade.php ENDPATH**/ ?>