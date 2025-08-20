<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add Transport Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="transportForm">
    <div class="modal-body">

        <div class="row clearfix transport_info mb-3" id="transport_info{{ $count }}">
            @if ($count > 1)
            <hr>
            @endif
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Transport Type</label>
                <select class="select2 form-control form-control-sm" name="transport[{{ $count }}][type]" required="" data-placeholder="select">
                    <option></option>
                    <option value="Arrival">Arrival</option>
                    <option value="Departure">Departure</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Airport</label>
                <select class="select2 form-control form-control-sm" name="transport[{{ $count }}][airport]" required="" data-placeholder="select">
                    <option></option>
                    <option value="Arrival">Arrival</option>
                    <option value="Departure">Departure</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Location</label>
                <input type="text" class="form-control" id="check_out_date_{{ $count }}"
                    name="transport[{{ $count }}][location]" value="" placeholder="Location">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Time</label>
                <input type="text" class="form-control total_nights"
                    name="transport[{{ $count }}][time]" value="" placeholder="Time">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Car Type</label>
                <select class="select2 form-control form-control-sm" name="transport[{{ $count }}][car_type]" required="" data-placeholder="select">
                    <option></option>
                    <option value="Arrival">Arrival</option>
                    <option value="Departure">Departure</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Sale Cost</label>
                <input type="number" class="form-control transport_sale_cost" name="transport[{{ $count }}][sale_cost]"
                    value="" placeholder="Sale Cost" step="0.01" required="">
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <label>Net Cost</label>
                <input type="number" class="form-control transport_net_cost" name="transport[{{ $count }}][net_cost]"
                    value="" placeholder="Net Cost" step="0.01" required="">
            </div>

            <div class="col-lg-4 col-md-6">
                <label>Supplier</label>
                <select class="select2 form-control form-control-sm" name="transport[{{ $count }}][supplier]" required="" data-placeholder="select">
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
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <span class="action_button" id="transport_buttons"></span>
            </div>
        </div>
    </div>
</form>

<div class="modal-footer">

    <button type="button" class="btn btn-primary" id="addTransportBtn">Add Details</button>
    <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i
            class="ri-close-line me-1 align-middle"></i> Close</a>
</div>

<script>
    $(document).ready(function() {

        var count = $('#transport_details_count').val();

        $('.modal .select2').select2({
            dropdownParent: $("#extraLargeModal")
        });

    });
    $(document).ready(function() {
        // Initialize modal
        // Handle Add Details button click
        $(document).on('click', '#addTransportBtn', function() {
            // Serialize the form data
            var newRow = $("#transport_info" + {{ $count }}).clone().attr('id', 'transport_html' +
                {{ $count }}).addClass('newTransportClass');

                // Convert cloned select elements to text inputs
            newRow.find('select.select2').each(function (key, value) {
                console.log(key, value, $(this).find(':selected').data(), $(this).find(':selected'), $(this).select2('data'));
                console.log($(this).select2('data')[0].text);
                // Create a new input element
                var inputElement = $('<input type="text">');
                // Set the value of the input to the selected value of the original select
                inputElement.val($(this).val());
                // Replace the select with the new input
                $(this).replaceWith(inputElement);
            });
            //console.log(newRow.find('.transport_net_cost').val());
            newRow.find("#transport_buttons").html(
                '<button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(transport_html{{ $count }})"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>'
            );

            $("#transport_details").append(newRow);
            $('#transport_details_count').val({{ $count }} + +1);

            //newRow.find("#transport_info"+{{ $count }}).removeAttr('id');
            $("#transportForm")[0].reset();
            $(".modal.extraLargeModal").modal("hide");

            //$('.select2').select2();
            //add_hotel_rows();
            // Embed hotel details into parent container
            //embedHotelDetails(formData);
        });

        {{ $count++ }}
    });
</script>
