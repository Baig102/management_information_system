<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Add / Update Hotel Actual Net</h5>

    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateHotelActualNet">
    <div class="modal-body" id="hotel_details">
        {{-- <pre>
            {{ print_r($booking) }}
            {{ print_r($vendors) }}
        </pre> --}}
        @php
            if ($booking->is_full_package == 0) {
                $filed_condition_sales_cost = 'required';
                $required_check_sales_cost = '<span class="text-danger"> *</span>';
            } else {
                $filed_condition_sales_cost = 'readonly';
                $required_check_sales_cost = '';
            }
        @endphp
        @csrf
        @foreach ($booking->hotels as $pass_key => $hotel)
        <div class="row clearfix hotel_info mb-3" id="hotelInfo_{{ $hotel->id }}">

            <div class="col-lg-3 col-md-6 mb-3">
                <label>Hotel Name<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[{{$hotel->id}}][hotel_name]" value="{{ $hotel->hotel_name }}" placeholder="Hotel Name" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Check in date<span class="text-danger"> *</span></label>
                <input type="text" class="form-control flatpickr check_in_date" id="checkInDate_{{$hotel->id}}" name="hotel[{{$hotel->id}}][check_in_date]" value="{{ $hotel->check_in_date }}" onchange="calculateAndDisplayDateDifference(this.id)" placeholder="Check in date" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Check out Date<span class="text-danger"> *</span></label>
                <input type="text" class="form-control flatpickr check_out_date" id="checkOutDate_{{$hotel->id}}" name="hotel[{{$hotel->id}}][check_out_date]" value="{{ $hotel->check_out_date }}" onchange="calculateAndDisplayDateDifference(this.id)" placeholder="Check out date" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Total nights<span class="text-danger"> *</span></label>
                <input type="text" class="form-control total_nights" id="total_nights_{{$hotel->id}}" name="hotel[{{$hotel->id}}][total_nights]" value="{{ $hotel->total_nights }}" placeholder="Total nights" readonly="" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Meal Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[{{$hotel->id}}][meal_type]" value="{{ $hotel->meal_type }}" placeholder="Meal Type" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Room Type<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="hotel[{{$hotel->id}}][room_type]" value="{{ $hotel->room_type }}" placeholder="Room Type" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>HCN <small>Hotel Confirmation Number</small></label>
                <input type="text" class="form-control" name="hotel[{{$hotel->id}}][hotel_confirmation_number]" value="{{ $hotel->hotel_confirmation_number }}" placeholder="HCN" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Sale Cost{!! $required_check_sales_cost !!}</label>
                <input type="number" class="form-control hotel_sale_cost" name="hotel[{{$hotel->id}}][sale_cost]" value="{{ $hotel->sale_cost }}" placeholder="Sale Cost" step="0.01" {{ $filed_condition_sales_cost }} readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control hotel_net_cost" name="hotel[{{$hotel->id}}][net_cost]" value="{{ $hotel->net_cost }}" placeholder="Net Cost" step="0.01" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label for="hotel_deadline" class="form-label">Hotel Deadline<span class="text-danger"> *</span></label>
                <input type="text" name="hotel[{{$hotel->id}}][deadline]" value="{{ $hotel->deadline }}" placeholder="DD-MM-YYYY" class="form-control flatpickr" data-provider="flatpickr" id="hotel_deadline_{{$hotel->id}}" autocomplete="off" readonly>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <label>Actual Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control actual_net_cost" name="hotel[{{$hotel->id}}][actual_net_cost]" value="{{ $hotel->actual_net_cost }}" placeholder="Actual Net Cost" step="0.01" required>
            </div>
            <div class="col-lg-3 col-md-6">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="hotelSupplier select form-control" id="hotelSupplier_{{$hotel->id}}" name="hotel[{{$hotel->id}}][supplier]" data-placeholder="select hotel supplier" required>
                    @foreach ($vendors as $v_key => $vendor)
                    <option value="{{ $vendor->name }}" {{ ($vendor->name == $hotel->supplier) ? "selected" : "" }}> {{$vendor->name}} </option>
                    @endforeach
                </select>

            </div>
            <input type="hidden" name="hotel[{{ $hotel->id }}][id]" value="{{ $hotel->id }}">
        </div>
        @endforeach
    </div>


<div class="modal-footer">
    <input type="hidden" name="booking_id" id="booking_id" value="{{ $booking->id }}">
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
            url: "{{ route('crm.update-hotel-actual-net') }}",
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
