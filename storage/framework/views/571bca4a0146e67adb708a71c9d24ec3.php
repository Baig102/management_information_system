<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add / Update Hotel Actual Net</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateHotelActualNet">
    <div class="modal-body" id="hotel_details">
        
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
        <?php $__currentLoopData = $booking->hotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pass_key => $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="row clearfix hotel_info mb-3" id="hotelInfo_<?php echo e($hotel->id); ?>">

            <div class="col-lg-3 col-md-6 mb-3">
                <label>Hotel Name<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[<?php echo e($hotel->id); ?>][hotel_name]" value="<?php echo e($hotel->hotel_name); ?>" placeholder="Hotel Name" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Check in date<span class="text-danger"> *</span></label>
                <input type="text" class="form-control flatpickr check_in_date" id="checkInDate_<?php echo e($hotel->id); ?>" name="hotel[<?php echo e($hotel->id); ?>][check_in_date]" value="<?php echo e($hotel->check_in_date); ?>" onchange="calculateAndDisplayDateDifference(this.id)" placeholder="Check in date" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Check out Date<span class="text-danger"> *</span></label>
                <input type="text" class="form-control flatpickr check_out_date" id="checkOutDate_<?php echo e($hotel->id); ?>" name="hotel[<?php echo e($hotel->id); ?>][check_out_date]" value="<?php echo e($hotel->check_out_date); ?>" onchange="calculateAndDisplayDateDifference(this.id)" placeholder="Check out date" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Total nights<span class="text-danger"> *</span></label>
                <input type="text" class="form-control total_nights" id="total_nights_<?php echo e($hotel->id); ?>" name="hotel[<?php echo e($hotel->id); ?>][total_nights]" value="<?php echo e($hotel->total_nights); ?>" placeholder="Total nights" readonly="" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Meal Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[<?php echo e($hotel->id); ?>][meal_type]" value="<?php echo e($hotel->meal_type); ?>" placeholder="Meal Type" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Room Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[<?php echo e($hotel->id); ?>][room_type]" value="<?php echo e($hotel->room_type); ?>" placeholder="Room Type" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>HCN <small>Hotel Confirmation Number</small></label>
                <input type="text" class="form-control" name="hotel[<?php echo e($hotel->id); ?>][hotel_confirmation_number]" value="<?php echo e($hotel->hotel_confirmation_number); ?>" placeholder="HCN" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Sale Cost<?php echo $required_check_sales_cost; ?></label>
                <input type="number" class="form-control hotel_sale_cost" name="hotel[<?php echo e($hotel->id); ?>][sale_cost]" value="<?php echo e($hotel->sale_cost); ?>" placeholder="Sale Cost" step="0.01" <?php echo e($filed_condition_sales_cost); ?> readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control hotel_net_cost" name="hotel[<?php echo e($hotel->id); ?>][net_cost]" value="<?php echo e($hotel->net_cost); ?>" placeholder="Net Cost" step="0.01" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label for="hotel_deadline" class="form-label">Hotel Deadline<span class="text-danger"> *</span></label>
                <input type="text" name="hotel[<?php echo e($hotel->id); ?>][deadline]" value="<?php echo e($hotel->deadline); ?>" placeholder="DD-MM-YYYY" class="form-control flatpickr" data-provider="flatpickr" id="hotel_deadline_<?php echo e($hotel->id); ?>" autocomplete="off" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Actual Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control actual_net_cost" name="hotel[<?php echo e($hotel->id); ?>][actual_net_cost]" value="<?php echo e($hotel->actual_net_cost); ?>" placeholder="Actual Net Cost" step="0.01" required>
            </div>
            <div class="col-lg-3 col-md-6">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="hotelSupplier select form-control" id="hotelSupplier_<?php echo e($hotel->id); ?>" name="hotel[<?php echo e($hotel->id); ?>][supplier]" data-placeholder="select hotel supplier" required>
                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v_key => $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($vendor->name); ?>" <?php echo e(($vendor->name == $hotel->supplier) ? "selected" : ""); ?>> <?php echo e($vendor->name); ?> </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

            </div>
            <input type="hidden" name="hotel[<?php echo e($hotel->id); ?>][id]" value="<?php echo e($hotel->id); ?>">
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>


<div class="modal-footer">
    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo e($booking->id); ?>">
    <button type="submit" class="btn btn-primary" id=""><i class="ri-save-3-line me-1 align-middle"></i>Update</button>
    <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
</form>

<script>
    $(function() {
        $('.select2').select2();
    })


      // Validate form before submitting
    $('#updateHotelActualNet').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('crm.update-hotel-actual-net')); ?>",
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
                    //title: response.title,
                    //icon: response.icon,
                    text: xhr.responseJSON.message,
                });
            }
        });
        return;
    });
</script>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/add-hotel-actual-net.blade.php ENDPATH**/ ?>