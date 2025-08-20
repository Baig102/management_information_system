<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Update Transport Details</h5>
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
        <div class="row clearfix transport_info mb-3" id="transportInfo_<?php echo e($transport->id); ?>">
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Transport Type<span class="text-danger"> *</span></label>
                <select class="select2 form-control" name="transport[<?php echo e($transport->id); ?>][transport_type]" required="" data-placeholder="select">
                    <option value="Arrival" <?php echo e(($transport->transport_type == 'Arrival') ? "selected" : ""); ?>>Arrival</option>
                    <option value="Departure" <?php echo e(($transport->transport_type == 'Departure') ? "selected" : ""); ?>>Departure</option>
                    <option value="Return" <?php echo e(($transport->transport_type == 'Return') ? "selected" : ""); ?>>Return</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Reference No</label>
                <input type="text" class="form-control" id="transportReferenceNo<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][reference_no]" value="<?php echo e($transport->reference_no); ?>" placeholder="Reference No">
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Airport<span class="text-danger"> *</span></label>
                
                <select class="airports form-control select2" id="transportAirport_<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][airport]" required>
                    <option value="<?php echo e($transport->airport); ?>"> <?php echo e($transport->airport); ?> </option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Location<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transportLocation<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][location]" value="<?php echo e($transport->location); ?>" placeholder="Location" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Date<span class="text-danger"> *</span></label>
                <input type="text" class="form-control transport_date" id="transport_date_<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][transport_date]" value="<?php echo e($transport->transport_date); ?>" placeholder="Date" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Time<span class="text-danger"> *</span></label>
                <input type="time" class="form-control time" name="transport[<?php echo e($transport->id); ?>][time]" value="<?php echo e($transport->time); ?>" placeholder="Time" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Car Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="transportCarType<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][car_type]" value="<?php echo e($transport->car_type); ?>" placeholder="Car Type" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Sale Cost<?php echo $required_check_sales_cost; ?></label>
                <input type="number" class="form-control transport_sale_cost" name="transport[<?php echo e($transport->id); ?>][sale_cost]" value="<?php echo e($transport->sale_cost); ?>" placeholder="Sale Cost" step="0.01" <?php echo e($filed_condition_sales_cost); ?>>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control transport_net_cost" name="transport[<?php echo e($transport->id); ?>][net_cost]" value="<?php echo e($transport->net_cost); ?>" placeholder="Net Cost" step="0.01" required="">
            </div>

            <div class="col-lg-2 col-md-6">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="transport_supplier form-control select2" id="transportSupplier_<?php echo e($transport->id); ?>" name="transport[<?php echo e($transport->id); ?>][supplier]" data-placeholder="select transport supplier" required>
                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v_key => $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($vendor->name); ?>" <?php echo e(($vendor->name == $transport->supplier) ? "selected" : ""); ?>> <?php echo e($vendor->name); ?> </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <input type="hidden" name="transport[<?php echo e($transport->id); ?>][id]" value="<?php echo e($transport->id); ?>">
                <button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row('transportInfo_<?php echo e($transport->id); ?>', <?php echo e($transport->id); ?>)"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>


    <div class="modal-footer">
        <input type="hidden" name="transport_count" id="transport_details_count" value="<?php echo e($booking->transports->count()+1); ?>">
        <input type="hidden" name="booking_id" id="booking_id" value="<?php echo e($booking->id); ?>">
        <a href="javascript:void(0)" class="btn btn-warning" id="addHotel" onclick="add_transport_rows(<?php echo e($booking->is_full_package); ?>)"><i class="ri-shield-user-fill me-1 align-middle"></i>Add</a>
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
            var startDatePicker = flatpickr(".transport_date", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });
            initializeSelect2WithAjax('.airports', '<?php echo e(route('crm.get-airports')); ?>', 'Search for airports', '.modal');
        });

    })
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    function add_transport_rows(isFullPackage) {

        transport_count = $('#transport_details_count').val();

        //$("#transportInfo_" + transport_count + " select").select2('destroy');

        const $transportContainer = $('#transport_details');
        //$transportContainer.empty();

        // Determine readonly and required status based on `isFullPackage`
        let fieldCondition = isFullPackage ? 'readonly' : 'required';
        let requiredCheck = isFullPackage ? '' : '<span class="text-danger"> *</span>';

        // Create a div for each date
        const $div = $(`<div class="row clearfix transport_info mb-3" id="transportInfo_${transport_count}"></div>`);

        // Create date input field
        const $dateInput = $(`
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Transport Type<span class="text-danger"> *</span></label>
                    <select class="form-control" name="transport[${transport_count}][transport_type]" required="" data-placeholder="select">
                        <option value="Arrival">Arrival</option>
                        <option value="Departure">Departure</option>
                        <option value="Return">Return</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Reference No</label>
                    <input type="text" class="form-control" id="transportReferenceNo${transport_count}" name="transport[${transport_count}][reference_no]" value="" placeholder="Reference No">
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Airport<span class="text-danger"> *</span></label>
                    <select class="airports form-select" id="transportAirport_${transport_count}" name="transport[${transport_count}][airport]" required></select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Location<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="transportLocation${transport_count}" name="transport[${transport_count}][location]" value="" placeholder="Location" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Date<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control transport_date" id="transport_date_${transport_count}" name="transport[${transport_count}][transport_date]" value="" placeholder="Date" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Time<span class="text-danger"> *</span></label>
                    <input type="time" class="form-control time" name="transport[${transport_count}][time]" value="" placeholder="Time" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Car Type<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="transportCarType${transport_count}" name="transport[${transport_count}][car_type]" value="" placeholder="Car Type" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Sale Cost${requiredCheck}</label>
                    <input type="number" class="form-control transport_sale_cost" name="transport[${transport_count}][sale_cost]" value="" placeholder="Sale Cost" step="0.01" ${fieldCondition}>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Net Cost<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control transport_net_cost" name="transport[${transport_count}][net_cost]" value="" placeholder="Net Cost" step="0.01" required="">
                </div>

                <div class="col-lg-2 col-md-6">
                    <label>Supplier<span class="text-danger"> *</span></label>
                    <select class="transport_supplier form-select select2" id="transportSupplier_${transport_count}" name="transport[${transport_count}][supplier]" data-placeholder="select transport supplier" required>
                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v_key => $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($vendor->name); ?>" > <?php echo e($vendor->name); ?> </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">

                        <input type="hidden" name="transport[${transport_count}][id]" value="">
                        <button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(transportInfo_${transport_count}, 'transport')"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>

                </div>
            `);

        $div.append($dateInput);
        // Append the div to the container
        $transportContainer.append($div);
        $(document).ready(function() {
            //console.log(flight_count);
            initializeSelect2WithAjax('#transportAirport_'+ transport_count, '<?php echo e(route('crm.get-airports')); ?>', 'Search for airports', '.modal');
        });

        $("#transportInfo_" + transport_count + " .select2").select2({
            dropdownParent: $('#extraLargeModal')
        });
        var startDatePicker = flatpickr("#transport_date_" + transport_count, {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $('#transport_details_count').val(parseInt(transport_count) + +1);

    };
    // Function to calculate and display the date difference
    function calculateAndDisplayDateDifference(id) {
        var row_num = id.split('_')[1];
        var startDate = $('#checkInDate_' + row_num).val(); //startDatePicker.selectedDates[0];
        var endDate = $('#checkOutDate_' + row_num).val(); //endDatePicker.selectedDates[0];
        console.log(id, row_num, startDate, endDate);

        if (startDate && endDate) {
            // Convert the string dates to date objects
            var startDateObj = new Date(startDate);
            var endDateObj = new Date(endDate);
            var dateDifference = Math.abs(endDateObj - startDateObj);
            //console.log(dateDifference);
            var daysDifference = Math.ceil(dateDifference / (1000 * 60 * 60 * 24));
            $('#total_nights_' + row_num).val(daysDifference);
            // Display the date difference
            //console.log(row_num, "Date Difference: " + daysDifference + " days");
        }
    }

    function remove_row(row_id, data_id = null) {

        var $row = $(row_id);
        //console.log(row_id, data_id);
        var transport_id = data_id;
        var booking_id = $('#booking_id').val();

        if (data_id != null) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform AJAX request to delete the payment
                    $.ajax({
                        url: '<?php echo e(route("crm.delete-transport-details", "")); ?>/' + transport_id,
                        type: 'DELETE',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>'
                        },
                        success: function(response) {
                            //console.log(response);
                            Swal.fire({
                                title: response.title,
                                icon: response.icon,
                                text: response.message,
                            });

                            $('.modal.extraLargeModal').modal('toggle');
                            $('.modal.fullscreeexampleModal').modal('toggle');
                            view_booking(booking_id);
                        },
                        error: function(response) {
                            Swal.fire({
                                text: xhr.responseJSON.message,
                            });
                        }
                    });
                }
            });
        }
        $row.remove();

    };

    // Validate form before submitting
    $('#updateTransportDetails').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('crm.update-transport-details')); ?>",
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
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/edit-transport.blade.php ENDPATH**/ ?>