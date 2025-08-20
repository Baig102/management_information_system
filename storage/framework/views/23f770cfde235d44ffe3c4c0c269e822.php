<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Update Hotel Details</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateHotelDetails">
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

            <div class="col-lg-2 col-md-6 mb-3">
                <label>Hotel Name<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[<?php echo e($hotel->id); ?>][hotel_name]" value="<?php echo e($hotel->hotel_name); ?>" placeholder="Hotel Name" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Check in date<span class="text-danger"> *</span></label>
                <input type="text" class="form-control flatpickr check_in_date" id="checkInDate_<?php echo e($hotel->id); ?>" name="hotel[<?php echo e($hotel->id); ?>][check_in_date]" value="<?php echo e($hotel->check_in_date); ?>" onchange="calculateAndDisplayDateDifference(this.id)" placeholder="Check in date" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Check out Date<span class="text-danger"> *</span></label>
                <input type="text" class="form-control flatpickr check_out_date" id="checkOutDate_<?php echo e($hotel->id); ?>" name="hotel[<?php echo e($hotel->id); ?>][check_out_date]" value="<?php echo e($hotel->check_out_date); ?>" onchange="calculateAndDisplayDateDifference(this.id)" placeholder="Check out date" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Total nights<span class="text-danger"> *</span></label>
                <input type="text" class="form-control total_nights" id="total_nights_<?php echo e($hotel->id); ?>" name="hotel[<?php echo e($hotel->id); ?>][total_nights]" value="<?php echo e($hotel->total_nights); ?>" placeholder="Total nights" readonly="" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Meal Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[<?php echo e($hotel->id); ?>][meal_type]" value="<?php echo e($hotel->meal_type); ?>" placeholder="Meal Type" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Room Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[<?php echo e($hotel->id); ?>][room_type]" value="<?php echo e($hotel->room_type); ?>" placeholder="Room Type" required>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>HCN <small>Hotel Confirmation Number</small></label>
                <input type="text" class="form-control" name="hotel[<?php echo e($hotel->id); ?>][hotel_confirmation_number]" value="<?php echo e($hotel->hotel_confirmation_number); ?>" placeholder="HCN">
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Sale Cost<?php echo $required_check_sales_cost; ?></label>
                <input type="number" class="form-control hotel_sale_cost" name="hotel[<?php echo e($hotel->id); ?>][sale_cost]" value="<?php echo e($hotel->sale_cost); ?>" placeholder="Sale Cost" step="0.01" <?php echo e($filed_condition_sales_cost); ?>>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control hotel_net_cost" name="hotel[<?php echo e($hotel->id); ?>][net_cost]" value="<?php echo e($hotel->net_cost); ?>" placeholder="Net Cost" step="0.01" required="">
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label for="hotel_deadline" class="form-label">Hotel Deadline<span class="text-danger"> *</span></label>
                <input type="text" name="hotel[<?php echo e($hotel->id); ?>][deadline]" value="<?php echo e($hotel->deadline); ?>" placeholder="DD-MM-YYYY" class="form-control flatpickr" data-provider="flatpickr" id="hotel_deadline_<?php echo e($hotel->id); ?>" autocomplete="off" required>
            </div>
            <div class="col-lg-2 col-md-6">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="hotelSupplier select form-control" id="hotelSupplier_<?php echo e($hotel->id); ?>" name="hotel[<?php echo e($hotel->id); ?>][supplier]" data-placeholder="select hotel supplier" required>
                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v_key => $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($vendor->name); ?>" <?php echo e(($vendor->name == $hotel->supplier) ? "selected" : ""); ?>> <?php echo e($vendor->name); ?> </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

            </div>
            
            <div class="col-lg-1 mt-3">
                <input type="hidden" name="hotel[<?php echo e($hotel->id); ?>][id]" value="<?php echo e($hotel->id); ?>">
                <button type="button" class="btn btn-danger waves-effect waves-light btn-soft-danger btn-sm clone_row mt-3"  onclick="remove_row('hotelInfo_<?php echo e($hotel->id); ?>', <?php echo e($hotel->id); ?>)"><i class="ri-indeterminate-circle-line align-middle fs-16 me-2"></i></button>
            </div>


        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>


<div class="modal-footer">
    <input type="hidden" name="hotel_count" id="hotel_details_count" value="<?php echo e($booking->hotels->count()+1); ?>">
    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo e($booking->id); ?>">
    <a href="javascript:void(0)" class="btn btn-warning" id="addHotel" onclick="add_hotel_rows(<?php echo e($booking->is_full_package); ?>)"><i class="ri-shield-user-fill me-1 align-middle"></i>Add</a>
    <button type="submit" class="btn btn-primary" id="updatePassenger"><i class="ri-save-3-line me-1 align-middle"></i>Update</button>
    <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
</form>

<script>
    $(function() {
        $('.select2').select2();
        $(".flatpickr").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
    })
    /* if ($('.cleave-date').length) {
        new Cleave('.cleave-date', {
            date: true,
            delimiter: '-',
            datePattern: ['d', 'm', 'Y']
        });
    } */


    var dateInputs = document.querySelectorAll('.cleave-date');

    dateInputs.forEach(function(input) {
        new Cleave(input, {
            date: true,
            delimiter: '-',
            datePattern: ['d', 'm', 'Y']
        });
    });



    function add_hotel_rows(isFullPackage) {
        //console.log(isFullPackage);
        hotel_count = $('#hotel_details_count').val();

        const $hotelContainer = $('#hotel_details');
        //$hotelContainer.empty();

        // Determine readonly and required status based on `isFullPackage`
        let fieldCondition = isFullPackage ? 'readonly' : 'required';
        let requiredCheck = isFullPackage ? '' : '<span class="text-danger"> *</span>';

        // Create a div for each date
        const $div = $(`<div class="row clearfix hotel_info mb-3" id="hotelInfo_${hotel_count}"></div>`);

        // Create date input field
        const $dateInput = $(`
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Hotel Name<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[${hotel_count}][hotel_name]" value="" placeholder="Hotel Name" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Check in date<span class="text-danger"> *</span></label>
                <input type="text" class="form-control flatpickr check_in_date" id="checkInDate_${hotel_count}" name="hotel[${hotel_count}][check_in_date]" value="" placeholder="Check in date" onchange="calculateAndDisplayDateDifference(this.id)" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Check out Date<span class="text-danger"> *</span></label>
                <input type="text" class="form-control flatpickr check_out_date" id="checkOutDate_${hotel_count}" name="hotel[${hotel_count}][check_out_date]" value="" placeholder="Check out date" onchange="calculateAndDisplayDateDifference(this.id)" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Total nights<span class="text-danger"> *</span></label>
                <input type="text" class="form-control total_nights" id="total_nights_${hotel_count}" name="hotel[${hotel_count}][total_nights]" value="" placeholder="Total nights" readonly="" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Meal Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[${hotel_count}][meal_type]" value="" placeholder="Meal Type" required>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Room Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[${hotel_count}][room_type]" value="" placeholder="Room Type" required>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>HCN <small>Hotel Confirmation Number</small></label>
                <input type="text" class="form-control" name="hotel[${hotel_count}][hotel_confirmation_number]" value="" placeholder="HCN">
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Sale Cost${requiredCheck}</label>
                <input type="number" class="form-control hotel_sale_cost" name="hotel[${hotel_count}][sale_cost]" value="" placeholder="Sale Cost" step="0.01" ${fieldCondition}>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control hotel_net_cost" name="hotel[${hotel_count}][net_cost]" value="" placeholder="Net Cost" step="0.01" required="">
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label for="hotel_deadline" class="form-label">Hotel Deadline<span class="text-danger"> *</span></label>
                <input type="text" name="hotel[${hotel_count}][deadline]" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr" data-provider="flatpickr" id="hotel_deadline_${hotel_count}" autocomplete="off" required>
            </div>
            <div class="col-lg-2 col-md-6">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="hotel_supplier select form-control" id="hotelSupplier_${hotel_count}" name="hotel[${hotel_count}][supplier]" data-placeholder="select hotel supplier" required>
                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v_key => $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($vendor->name); ?>"> <?php echo e($vendor->name); ?> </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

            </div>
            <div class="col-lg-1 col-md-6 mb-3">
                <input type="hidden" name="hotel[${hotel_count}][id]" value="">
                <button type="button" class="btn btn-danger waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(hotel_info_${hotel_count}, 'hotel')"><i class="ri-indeterminate-circle-line align-middle fs-16 me-2"></i></button>
            </div>
        `);
        $div.append($dateInput);

        $(document).ready(function() {
            // Initialize Flatpickr for the start date input
            var startDatePicker = flatpickr("#checkInDate_" + hotel_count, {
                dateFormat: "Y-m-d",
                minDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    // Set the selected date of the start date input as the min date for the end date input
                    endDatePicker.set('minDate', selectedDates[0]);
                    // Calculate and display the date difference
                    //calculateAndDisplayDateDifference();
                }
            });

            // Initialize Flatpickr for the end date input
            var endDatePicker = flatpickr("#checkOutDate_" + hotel_count, {
                dateFormat: "Y-m-d",
                minDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    // Set the selected date of the end date input as the max date for the start date input
                    startDatePicker.set('maxDate', selectedDates[0]);
                    // Calculate and display the date difference
                    //calculateAndDisplayDateDifference();
                }
            });

            flatpickr("#hotel_deadline_" + hotel_count, {
                date: true,
                delimiter: '-',
                datePattern: ['d', 'm', 'Y']
            });


        });

        // Append the div to the container
        $hotelContainer.append($div);

        $('#hotel_details_count').val(parseInt(hotel_count) + +1);

        //initializeSelect2WithAjax('#hotelSupplier_'+ hotel_count, '<?php echo e(route('ams.get-vendors','Hotels')); ?>', 'Search for Vendor');
        //checkCheckboxState();
    };
    // Function to calculate and display the date difference
    function calculateAndDisplayDateDifference(id) {
        var row_num = id.split('_')[1];
        var startDate = $('#checkInDate_'+row_num).val(); //startDatePicker.selectedDates[0];
        var endDate = $('#checkOutDate_'+row_num).val(); //endDatePicker.selectedDates[0];
        //console.log(id,row_num,startDate,endDate);

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

    function remove_row(row_id, data_id=null) {

        var $row = $(row_id);
        //console.log(row_id, data_id);
        var hotel_id = data_id;
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
                        url: '<?php echo e(route("crm.delete-hotel-details", "")); ?>/' + hotel_id,
                        type: 'DELETE',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>'
                        },
                        success: function (response) {
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
                        error: function (response) {
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
    $('#updateHotelDetails').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('crm.update-hotel-details')); ?>",
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
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/edit-hotel.blade.php ENDPATH**/ ?>