<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title">Update Flight Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateFlight">
    <div class="modal-body" id="flight_details" style="max-height: 90vh; overflow-y: auto;">

        @csrf
        <div class="card-header bg-light-subtle border-bottom-dashed mb-3">
            <div class="row py-2">
                <div class="col-lg-2 col-sm-6">
                    <div class="form-check card-radio">
                        <input id="one_way" name="trip_type" type="radio" class="form-check-input" value="1" {{ ($booking->trip_type == 1) ? "checked" : "" }}>
                        <label class="form-check-label p-2" for="one_way">
                            <span class="fs-16 text-muted me-2"><i class="ri-flight-takeoff-line align-bottom"></i></span>
                            <span class="fs-14 text-wrap">One Way</span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="form-check card-radio">
                        <input id="return" name="trip_type" type="radio" class="form-check-input" value="2" {{ ($booking->trip_type == 2) ? "checked" : "" }}>
                        <label class="form-check-label p-2" for="return">
                            <span class="fs-16 text-muted me-2"><i class="ri-flight-land-line align-bottom"></i></span>
                            <span class="fs-14 text-wrap">Return</span>
                        </label>
                    </div>
                </div>

                <div class="col-lg-2 col-sm-6">
                    <div class="form-check card-radio">
                        <input id="direct" name="flight_type" type="radio" class="form-check-input" value="1" {{ ($booking->flight_type == 1) ? "checked" : "" }}>
                        <label class="form-check-label p-2" for="direct">
                            <span class="fs-16 text-muted me-2"><i class="ri-guide-line align-bottom"></i></span>
                            <span class="fs-14 text-wrap">Direct</span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="form-check card-radio">
                        <input id="in_direct" name="flight_type" type="radio" class="form-check-input" value="2" {{ ($booking->flight_type == 2) ? "checked" : "" }}>
                        <label class="form-check-label p-2" for="in_direct">
                            <span class="fs-16 text-muted me-2"><i class="ri-route-line align-bottom"></i></span>
                            <span class="fs-14 text-wrap">In Direct</span>
                        </label>
                    </div>
                </div>

            </div>
        </div>
        {{-- <pre>
        {{ print_r($booking->flights) }}
        </pre> --}}
        <div class="card-body" id="flight_details">
        @foreach ($booking->flights as $flight)
            <div class="row clearfix flight_info mb-3" id="flightInfo_{{$flight->id}}">
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>GDS Ref. No.</label>
                    <input type="text" class="form-control" name="flights[{{$flight->id}}][gds_no]" value="{{ $flight->gds_no }}" placeholder="GDS Ref. Number">
                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Airline Locator</label>
                    <input type="text" class="form-control" name="flights[{{$flight->id}}][airline_locator]" value="{{ $flight->airline_locator }}" placeholder="Airline Locator">
                </div>
                <div class="col-lg-2 col-md-2 mb-3 d-none">
                    <label>Ticket Number</label>
                    <input type="text" class="form-control" name="flights[{{$flight->id}}][ticket_no]" value="{{ $flight->ticket_no }}" placeholder="Ticket Number">
                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Flight No</label>
                    <input type="text" class="form-control" name="flights[{{$flight->id}}][flight_number]" value="{{ $flight->flight_number }}" placeholder="Flight No" {{ ($flight->pnr_id != null) ? "readonly" : "" }}>
                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Departure Airport</label>
                    @if ($flight->pnr_id != null)
                        <input type="text" class="form-control" id="departure_airport_{{$flight->id}}" name="flights[{{$flight->id}}][departure_airport]" value="{{ $flight->departure_airport }}" readonly>
                    @else
                        <select class="departure_airports form-control select2" id="departure_airport_{{$flight->id}}" name="flights[{{$flight->id}}][departure_airport]">
                            <option value="{{$flight->departure_airport}}">{{$flight->departure_airport}}</option>
                        </select>
                    @endif
                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Arrival Airport</label>
                    @if ($flight->pnr_id != null)
                        <input type="text" class="form-control" id="arrival_airport_{{$flight->id}}" name="flights[{{$flight->id}}][arrival_airport]" value="{{ $flight->arrival_airport }}" readonly>
                    @else
                        <select class="arrival_airports form-control select2" id="arrival_airport_{{$flight->id}}" name="flights[{{$flight->id}}][arrival_airport]">
                            <option value="{{$flight->arrival_airport}}">{{$flight->arrival_airport}}</option>
                        </select>
                    @endif

                </div>

                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Departure Date</label>
                    <input type="text" class="form-control {{ ($flight->pnr_id != null) ? "" : "departureDate" }} " id="departure_date_{{$flight->id}}" name="flights[{{$flight->id}}][departure_date]" value="{{ $flight->departure_date }}" placeholder="Departure Date" {{ ($flight->pnr_id != null) ? "readonly" : "" }}>
                </div>

                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Arrivel Date</label>
                    <input type="text" class="form-control {{ ($flight->pnr_id != null) ? "" : "departureDate" }}" id="arrival_date_{{$flight->id}}" name="flights[{{$flight->id}}][arrival_date]" value="{{ $flight->arrival_date }}" placeholder="Arrivel Date" {{ ($flight->pnr_id != null) ? "readonly" : "" }}>
                </div>

                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Departure Time</label>
                    <input type="time" class="form-control" id="departure_time_{{$flight->id}}" name="flights[{{$flight->id}}][departure_time]" value="{{ $flight->departure_time }}" placeholder="Departure Time" {{ ($flight->pnr_id != null) ? "readonly" : "" }}>
                </div>

                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Arrivel Time</label>
                    <input type="time" class="form-control" id="arrival_time_{{$flight->id}}" name="flights[{{$flight->id}}][arrival_time]" value="{{ $flight->arrival_time }}" placeholder="Arrivel Time" {{ ($flight->pnr_id != null) ? "readonly" : "" }}>
                </div>

                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Airline Name</label>
                    @if ($flight->pnr_id != null)
                        <input type="text" class="form-control" id="air_line_name_{{$flight->id}}" name="flights[{{$flight->id}}][air_line_name]" value="{{ $flight->air_line_name }}" readonly>
                    @else
                        <select class="air_line_name form-control select2" id="air_line_name_{{$flight->id}}" name="flights[{{$flight->id}}][air_line_name]" {{ ($flight->pnr_id != null) ? "disabled" : "" }}>
                            @foreach ($airlines as $airline)
                            <option value="{{$airline->name}}" {{ ($flight->air_line_name == $airline->name) ? "selected" : "" }}>{{$airline->name}}</option>
                            @endforeach
                        </select>
                    @endif

                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Booking Class</label>
                    @if ($flight->pnr_id != null)
                        <input type="text" class="form-control" id="" name="flights[{{$flight->id}}][booking_class]" value="{{ $flight->booking_class }}" placeholder="Booking Class" readonly>
                    @else
                    <select class="select2 form-control" name="flights[{{$flight->id}}][booking_class]" required="" data-placeholder="Select Booking Class">
                        <option></option>
                        <option {{ ($flight->booking_class == 'ECONOMY CLASS') ? "selected" : "" }}>ECONOMY CLASS</option>
                        <option {{ ($flight->booking_class == 'ECONOMY PLUS') ? "selected" : "" }}>ECONOMY PLUS</option>
                        <option {{ ($flight->booking_class == 'PREMIUM ECONOMY') ? "selected" : "" }}>PREMIUM ECONOMY</option>
                        <option {{ ($flight->booking_class == 'BUSINESS CLASS') ? "selected" : "" }}>BUSINESS CLASS</option>
                        <option {{ ($flight->booking_class == 'FIRST CLASS') ? "selected" : "" }}>FIRST CLASS</option>
                    </select>
                    @endif

                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label># of Baggage</label>
                    <select class="form-control select2" name="flights[{{$flight->id}}][number_of_baggage]" required="" data-placeholder="select # of Baggage">
                        <option {{ ($flight->number_of_baggage == '0 PC') ? "selected" : "" }}>0 PC</option>
                        <option {{ ($flight->number_of_baggage == '01 PC x 23kg') ? "selected" : "" }}>01 PC x 23kg</option>
                        <option {{ ($flight->number_of_baggage == '02 PC x 23kg') ? "selected" : "" }}>02 PC x 23kg</option>
                        <option {{ ($flight->number_of_baggage == '03 PC x 23kg') ? "selected" : "" }}>03 PC x 23kg</option>
                        <option {{ ($flight->number_of_baggage == '15 KG') ? "selected" : "" }}>15 KG</option>
                        <option {{ ($flight->number_of_baggage == '20 KG') ? "selected" : "" }}>20 KG</option>
                        <option {{ ($flight->number_of_baggage == '23 KG') ? "selected" : "" }}>23 KG</option>
                        <option {{ ($flight->number_of_baggage == '25 KG') ? "selected" : "" }}>25 KG</option>
                        <option {{ ($flight->number_of_baggage == '30 KG') ? "selected" : "" }}>30 KG</option>
                        <option {{ ($flight->number_of_baggage == '35 KG') ? "selected" : "" }}>35 KG</option>
                        <option {{ ($flight->number_of_baggage == '40 KG') ? "selected" : "" }}>40 KG</option>
                        <option {{ ($flight->number_of_baggage == '45 KG') ? "selected" : "" }}>45 KG</option>
                        <option {{ ($flight->number_of_baggage == '46 KG') ? "selected" : "" }}>46 KG</option>
                    </select>
                </div>
                <div class="col-lg-1 col-md-1 mb-3">
                    <input type="hidden" name="flights[{{ $flight->id }}][id]" value="{{ $flight->id }}">
                    @if ( $flight->pnr_id == null)
                        <button type="button"
                            class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(flightInfo_{{$flight->id}}, {{$flight->id}})"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
                    @endif
                </div>

            </div>
            <hr>
        @endforeach
        </div>
    </div>


<div class="modal-footer">
    <input type="hidden" name="flight_count" id="flight_details_count" value="{{ $booking->flights->count()+1 }}">
    <input type="hidden" name="booking_id" id="booking_id" value="{{ $booking->id }}">
    @if ($booking->is_pnr_booking == 0)
    <a href="javascript:void(0)" class="btn btn-warning" id="addFlight" onclick="add_flight_rows()"><i class="ri-shield-user-fill me-1 align-middle"></i>Add</a>
    @endif
    <button type="submit" class="btn btn-primary" id="updateFlight"><i class="ri-save-3-line me-1 align-middle"></i>Update</button>
    <a href="javascript:void(0);" class="btn btn-danger fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
</div>
</form>

<script>
    $(function() {
        //$('.select2').select2();
        $(".flatpickr-date").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $('#extraLargeModal').on('shown.bs.modal', function () {
            //console.log('ok load');
            $('.select2').select2({
                dropdownParent: $('#extraLargeModal')
            });
            initializeSelect2WithAjax('.departure_airports', '{{ route('crm.get-airports') }}', 'Search for airports', '.modal');
            initializeSelect2WithAjax('.arrival_airports', '{{ route('crm.get-airports') }}', 'Search for airports', '.modal');
        });

    })
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });
    $(document).ready(function() {
        // Initialize Flatpickr for the start date input
        var startDatePicker = flatpickr(".departureDate", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
        var startDatePicker = flatpickr(".arrivalDate", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
    });
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

    function add_flight_rows() {

        flight_count = $('#flight_details_count').val();

        //$("#flightInfo_" + flight_count + " select").select2('destroy');

        const $flightContainer = $('#flight_details');
        //$flightContainer.empty();

        // Create a div for each date
        const $div = $(`<div class="row clearfix flight_info mb-3" id="flightInfo_${flight_count}"><hr><input type="hidden" name="flights[${flight_count}][id]" value=""></div>`);

        // Create date input field
        const $dateInput = $(`
            <div class="col-lg-2 col-md-2 mb-3">
                <label>GDS Ref. No.</label>
                <input type="text" class="form-control" name="flights[${flight_count}][gds_no]" placeholder="GDS Ref. Number">
            </div>
            <div class="col-lg-2 col-md-2 mb-3">
                <label>Airline Locator</label>
                <input type="text" class="form-control" name="flights[${flight_count}][airline_locator]" placeholder="Airline Locator">
            </div>
            <div class="col-lg-2 col-md-2 mb-3 d-none">
                <label>Ticket Number</label>
                <input type="text" class="form-control" name="flights[${flight_count}][ticket_no]" placeholder="Ticket Number">
            </div>
            <div class="col-lg-2 col-md-2 mb-3">
                <label>Flight No</label>
                <input type="text" class="form-control" name="flights[${flight_count}][flight_number]" placeholder="Flight No">
            </div>
            <div class="col-lg-2 col-md-2 mb-3">
                <label>Departure Airport</label>
                <select class="departure_airports" id="departure_airport_${flight_count}" name="flights[${flight_count}][departure_airport]"></select>
            </div>
            <div class="col-lg-2 col-md-2 mb-3">
                <label>Arrival Airport</label>
                <select class="arrival_airports" id="arrival_airport_${flight_count}" name="flights[${flight_count}][arrival_airport]"></select>
            </div>

            <div class="col-lg-2 col-md-2 mb-3">
                <label>Departure Date</label>
                <input type="text" class="form-control" id="departure_date_${flight_count}" name="flights[${flight_count}][departure_date]" value="" placeholder="Departure Date">
            </div>

            <div class="col-lg-2 col-md-2 mb-3">
                <label>Arrivel Date</label>
                <input type="text" class="form-control" id="arrival_date_${flight_count}" name="flights[${flight_count}][arrival_date]" value="" placeholder="Arrivel Date">
            </div>

            <div class="col-lg-2 col-md-2 mb-3">
                <label>Departure Time</label>
                <input type="time" class="form-control" id="departure_time_${flight_count}" name="flights[${flight_count}][departure_time]" value="" placeholder="Departure Time">
            </div>

            <div class="col-lg-2 col-md-2 mb-3">
                <label>Arrivel Time</label>
                <input type="time" class="form-control" id="arrival_time_${flight_count}" name="flights[${flight_count}][arrival_time]" value="" placeholder="Arrivel Time">
            </div>

            <div class="col-lg-2 col-md-2 mb-3">
                <label>Airline Name</label>
                <select class="air_line_name form-control select2" id="air_line_name_${flight_count}" name="flights[${flight_count}][air_line_name]">
                    @foreach ($airlines as $airline)
                        <option value="{{$airline->name}}">{{$airline->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-2 mb-3">
                <label>Booking Class</label>
                <select class="select2 form-control" name="flights[${flight_count}][booking_class]" required="" data-placeholder="Select Booking Class">
                    <option></option>
                    <option>ECONOMY CLASS</option>
                    <option>ECONOMY PLUS</option>
                    <option>PREMIUM ECONOMY</option>
                    <option>BUSINESS CLASS</option>
                    <option>FIRST CLASS</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 mb-3">
                <label># of Baggage</label>
                <select class="select2 form-control" name="flights[${flight_count}][number_of_baggage]" required="" data-placeholder="select # of Baggage">
                    <option>0 PC</option>
                    <option>01 PC</option>
                    <option>02 PC</option>
                    <option>03 PC</option>
                    <option>23 KG</option>
                    <option>25 KG</option>
                    <option>30 KG</option>
                    <option>35 KG</option>
                    <option>40 KG</option>
                    <option>45 KG</option>
                    <option>46 KG</option>
                </select>
            </div>

            <div class="col-lg-1 col-md-1 mb-3">
                    <button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(flightInfo_${flight_count})"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
            </div>

        `);

        $div.append($dateInput);
        // Append the div to the container
        $flightContainer.append($div);
        //$("#flightInfo_" + flight_count + " select").select2();

        /* initializeSelect2WithAjax('#departure_airport_' + flight_count, '{{ route('crm.get-airports') }}',
            'Search for airports');
        initializeSelect2WithAjax('#arrival_airport_' + flight_count, '{{ route('crm.get-airports') }}',
            'Search for airports');
        initializeSelect2WithAjax('#air_line_name_' + flight_count, '{{ route('crm.get-airlines') }}',
            'Search for airlines'); */

        /* $('#extraLargeModal').on('shown.bs.modal', function () {
            //console.log('okok');
            $('.select2').select2({
                dropdownParent: $('#extraLargeModal')
            });
        }); */


            //initializeSelect2WithAjax('#departure_airport_'+ flight_count, '{{ route('crm.get-airports') }}', 'Search for airports');
        /* $(document).ready(function() {
            initializeSelect2WithAjax('#departure_airport_'+ flight_count, '{{ route('crm.get-airports') }}', 'Search for airports');
            //initializeSelect2WithAjax('#arrival_airport_'+ flight_count, '{{ route('crm.get-airports') }}', 'Search for airports');
            // Initialize Flatpickr for the start date input
            var startDatePicker = flatpickr("#departure_date_" + flight_count, {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });
            var startDatePicker = flatpickr("#arrival_date_" + flight_count, {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });
        });
        $("#flightInfo_" + flight_count + " .select2").select2({
            dropdownParent: $('#extraLargeModal')
        }); */

        $(document).ready(function() {
            //console.log(flight_count);
            initializeSelect2WithAjax('#departure_airport_'+ flight_count, '{{ route('crm.get-airports') }}', 'Search for airports', '.modal');
            initializeSelect2WithAjax('#arrival_airport_'+ flight_count, '{{ route('crm.get-airports') }}', 'Search for airports', '.modal');
            // Initialize Flatpickr for the start date input
            var startDatePicker = flatpickr("#departure_date_" + flight_count, {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });
            var startDatePicker = flatpickr("#arrival_date_" + flight_count, {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });
        });

        $("#flightInfo_" + flight_count + " .select2").select2({
            dropdownParent: $('#extraLargeModal')
        });

        $('#flight_details_count').val(parseInt(flight_count) + +1);
    };

    function remove_row(row_id, data_id=null) {

        var $row = $(row_id);
        //console.log(row_id, data_id);
        var flight_id = data_id;
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
                        url: '{{ route("crm.delete-flight-details", "") }}/' + flight_id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
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
                            //console.log(response);
                            Swal.fire({
                                title: response.responseJSON.title,
                                icon: response.responseJSON.icon,
                                text: response.responseJSON.message,
                            });
                        }
                    });
                }
            });
        }
        $row.remove();

    };

      // Validate form before submitting
    $('#updateFlight').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{ route('crm.update-flight-details') }}",
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
