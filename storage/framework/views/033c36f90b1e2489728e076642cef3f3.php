<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="">Update Booking Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateBookingInformation">
    <div class="modal-body" id="">
        <?php echo csrf_field(); ?>
        <div class="row">
            

            <div class="col-lg-2">
                <label for="booking_date" class="form-label">Booking Date<span class="text-danger"> *</span></label>
                <input type="text" name="booking_date" value="<?php echo e($booking->booking_date); ?>" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="booking_date" autocomplete="off"
                    required>
            </div>

            <div class="col-lg-2">
                <label for="ticket_deadline" class="form-label">Ticketing Deadline<span class="text-danger">
                        *</span></label>
                <input type="text" name="ticket_deadline" value="<?php echo e($booking->ticket_deadline); ?>" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="ticket_deadline"
                    autocomplete="off" required>
            </div>

            <div class="col-lg-2 col-sm-6">
                <label class="form-label">Non Refundable Ticket</label>
                <div class="form-check card-radio">
                    <input id="non_refundable" name="booking_payment_term" type="radio" class="form-check-input" value="1" <?php echo e(($booking->booking_payment_term == 1) ? "checked" : ""); ?>>
                    <label class="form-check-label p-2" for="non_refundable">
                        <span class="fs-16 text-muted me-2"><i class="ri-secure-payment-line align-bottom"></i></span>
                        <span class="fs-14 text-wrap">Non Refundable</span>
                    </label>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6">
                <label class="form-label">Refundable Ticket</label>
                <div class="form-check card-radio">
                    <input id="refundable" name="booking_payment_term" type="radio" class="form-check-input" value="2" <?php echo e(($booking->booking_payment_term == 2) ? "checked" : ""); ?>>
                    <label class="form-check-label p-2" for="refundable">
                        <span class="fs-16 text-muted me-2"><i class="ri-refund-line align-bottom"></i></span>
                        <span class="fs-14 text-wrap">Refundable</span>
                    </label>
                </div>
            </div>
            <div class="col-lg-2">
                <label for="number_of_installments" class="form-label"># of Installments<span class="text-danger"> *</span></label>
                <select class="form-control ms select2" name="total_installment" id="total_installment" data-placeholder="Select Number of Installments">
                    <option></option>
                    <?php for($i=1; $i<=20; $i++): ?>
                        <option value="<?php echo e($i); ?>" <?php echo e(($booking->total_installment == $i) ? "selected" : ""); ?>><?php echo e($i); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="currency_unit" class="form-label">Currency<span class="text-danger"> *</span></label>
                <select class="form-control ms select2" name="currency" id="currency_unit" data-placeholder="Select currency" required>
                    <option value="Rs" <?php echo e(($booking->currency == 'Rs') ? "selected" : ""); ?>>Rs</option>
                    <option value="$" <?php echo e(($booking->currency == '$') ? "selected" : ""); ?>>$</option>
                    <option value="£" <?php echo e(($booking->currency == '£') ? "selected" : ""); ?>>£</option>
                    <option value="€" <?php echo e(($booking->currency == '€') ? "selected" : ""); ?>>€</option>
                </select>
            </div>
            <div class="col-lg-2 col-sm-6">
                <label for="is_full_package" class="form-label">Is Full Package?</label>
                <div class="form-check form-switch form-switch-custom form-switch-primary">
                    <input class="form-check-input" type="checkbox" name="is_full_package" role="switch" id="is_full_package" <?php echo e(($booking->is_full_package == 1) ? "checked" : ""); ?>>
                    <label class="form-check-label" for="is_full_package">Yes</label>
                </div>
            </div>

        </div>

    </div>


    <div class="modal-footer">

        <input type="hidden" name="booking_id" id="booking_id" value="<?php echo e($booking->id); ?>">
        <button type="submit" class="btn btn-primary" id="updatePassenger"><i class="ri-save-3-line me-1 align-middle"></i>Update</button>
        <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    </div>
</form>

<script>
    $(function() {
        $('.select2').select2();
        $(".flatpickr-date").flatpickr({
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


    // Validate form before submitting
    $('#updateBookingInformation').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('crm.update-booking-information')); ?>",
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
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/edit-booking-information.blade.php ENDPATH**/ ?>