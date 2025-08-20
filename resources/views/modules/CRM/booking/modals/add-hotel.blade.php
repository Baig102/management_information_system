<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add Hotel Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="hotelForm">
    <div class="modal-body">

        <div class="row clearfix hotel_info mb-3" id="hotel_info{{ $count }}">
            @if ($count > 1)
                <hr>
            @endif
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Hotel Name</label>
                <input type="text" class="form-control" name="hotel[{{ $count }}][hotel_name]" value=""
                    placeholder="Hotel Name">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Check in date</label>
                <input type="text" class="form-control flatpickr check_in_date" id="check_in_date_{{ $count }}"
                    name="hotel[{{ $count }}][check_in_date]" value="" placeholder="Check in date">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Check out Date</label>
                <input type="text" class="form-control flatpickr check_out_date" id="check_out_date_{{ $count }}"
                    name="hotel[{{ $count }}][check_out_date]" value="" placeholder="Check out date">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Total nights</label>
                <input type="text" class="form-control total_nights"
                    name="hotel[{{ $count }}][total_nights]" value="" placeholder="Total nights"
                    readonly="">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Meal Type</label>
                <input type="text" class="form-control" name="hotel[{{ $count }}][meal_type]" value=""
                    placeholder="Meal Type">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Room Type</label>
                <input type="text" class="form-control" name="hotel[{{ $count }}][room_type]" value=""
                    placeholder="Room Type">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Sale Cost</label>
                <input type="number" class="form-control hotel_sale_cost" name="hotel[{{ $count }}][sale_cost]"
                    value="" placeholder="Sale Cost" step="0.01" required="">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Net Cost</label>
                <input type="number" class="form-control hotel_net_cost" name="hotel[{{ $count }}][net_cost]"
                    value="" placeholder="Net Cost" step="0.01" required="">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <span class="action_button"></span>
            </div>
            {{-- <div class="col-lg-4 col-md-6">
                <label>Supplier</label>
                <select class="select2 form-control form-control-sm" name="hotel[{{ $count }}][supplier]" required="" data-placeholder="select">
                    <option></option>
                    <option value="2">BTRS </option>
                    <option value="3">AMR </option>
                    <option value="4">173 PENDING BTRS </option>
                    <option value="5">FLYSHARP </option>
                    <option value="6">LYCAFLY </option>
                    <option value="7">TRAVEL PACK </option>
                    <option value="8">TS </option>
                    <option value="9">POLANI </option>
                    <option value="10">AHMED TRANSPORT </option>
                    <option value="11">SAUDI PORTAL </option>
                </select>
            </div> --}}
        </div>
    </div>
</form>

<div class="modal-footer">
    {{-- <div class="row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary"> {{ __('Register') }} </button>
            </div>
        </div> --}}
    {{-- <a href="javascript:void(0);" class="btn btn-primary" onclick="embed_row()">Add</a> --}}
    <button type="button" class="btn btn-primary" id="addHotelBtn">Add Details</button>
    <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i
            class="ri-close-line me-1 align-middle"></i> Close</a>
</div>

<script>
    $(document).ready(function() {

        var count = $('#hotel_details_count').val();

        $('.select2').select2({
            dropdownParent: $("#extraLargeModal")
        });

        // $(".check_in_date").flatpickr({
        //     altInput: true,
        //     altFormat: "F j, Y",
        //     dateFormat: "Y-m-d",
        //     minDate: "today",
        //     onChange: function(selectedDates, dateStr, instance) {
        //         console.log(selectedDates, dateStr, instance);
        //         $(".check_out_date").set("maxDate", dateStr);
        //     }
        // });

        // Initialize Flatpickr for the start date input
        /* var check_in_date = flatpickr(".flatpickr", {
            enableTime: false, // Set to true if you want to include time
            dateFormat: "Y-m-d",
            minDate: "today",
        }); */

    });
    $(document).ready(function() {
        // Initialize Flatpickr for the start date input
        var startDatePicker = flatpickr("#check_in_date_{{ $count }}", {
            dateFormat: "Y-m-d",
            minDate: "today",
            onChange: function (selectedDates, dateStr, instance) {
                // Set the selected date of the start date input as the min date for the end date input
                endDatePicker.set('minDate', selectedDates[0]);
                // Calculate and display the date difference
                calculateAndDisplayDateDifference();
            }
        });

        // Initialize Flatpickr for the end date input
        var endDatePicker = flatpickr("#check_out_date_{{ $count }}", {
            dateFormat: "Y-m-d",
            minDate: "today",
            onChange: function (selectedDates, dateStr, instance) {
                // Set the selected date of the end date input as the max date for the start date input
                startDatePicker.set('maxDate', selectedDates[0]);
                // Calculate and display the date difference
                calculateAndDisplayDateDifference();
            }
        });

        // Function to calculate and display the date difference
        function calculateAndDisplayDateDifference() {
            var startDate = startDatePicker.selectedDates[0];
            var endDate = endDatePicker.selectedDates[0];

            if (startDate && endDate) {
                var dateDifference = Math.abs(endDate - startDate);
                var daysDifference = Math.ceil(dateDifference / (1000 * 60 * 60 * 24));
                $('.total_nights').val(daysDifference);
                // Display the date difference
                console.log("Date Difference: " + daysDifference + " days");
            }
        }
    });
    $(document).ready(function() {
        // Initialize modal

        // Handle Add Details button click
        $(document).on('click', '#addHotelBtn', function() {
            // Serialize the form data
            var newRow = $("#hotel_info" + {{ $count }}).clone().attr('id', 'hotel_html' +
                {{ $count }}).addClass('newClass');
            newRow.find(".action_button").html(
                '<button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(hotel_html{{ $count }})"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>'
            );

            $("#hotel_details").append(newRow);
            $('#hotel_details_count').val({{ $count }} + +1);

            //newRow.find("#hotel_info"+{{ $count }}).removeAttr('id');
            $("#hotelForm")[0].reset();
            $(".modal.extraLargeModal").modal("hide");
            //$('.select2').select2();
            //add_hotel_rows();
            // Embed hotel details into parent container
            //embedHotelDetails(formData);
        });

        {{ $count++ }}
    });
</script>
