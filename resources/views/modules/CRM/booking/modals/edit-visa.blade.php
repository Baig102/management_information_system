<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Update Visa Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="updateVisaDetails">
    <div class="modal-body" id="visa_details">
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
        @foreach ($booking->visas as $pass_key => $visa)

        <div class="row clearfix visa_info mb-3" id="visaInfo_{{ $visa->id }}">
            <div class="col-lg-2 col-md-6">
                <label>Visa Category<span class="text-danger"> *</span></label>
                <select class="visa_category select2" id="visaCategory_{{ $visa->id }}" name="visa[{{ $visa->id }}][visa_category]" data-placeholder="select visa visa category" required>
                    @foreach ($visaCategories as $a_key => $visa_category)
                        <option value="{{ $visa_category->name }}" {{ ($visa_category->name == $visa->visa_category) ? "selected" : "" }}> {{$visa_category->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label>Visa Country<span class="text-danger"> *</span></label>
                <select class="visa_country select2" id="visaCountry_{{ $visa->id }}" name="visa[{{ $visa->id }}][visa_country]" data-placeholder="select visa visa country" required>
                    @foreach ($countries as $c_key => $country)
                        <option value="{{ $country->name }}" {{ ($country->name == $visa->visa_country) ? "selected" : "" }}> {{$country->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Number of Pax<span class="text-danger"> *</span></label>
                <input type="number" class="form-control no_of_pax" id="noOfPax_{{ $visa->id }}" name="visa[{{ $visa->id }}][no_of_pax]" value="{{ $visa->no_of_pax }}" placeholder="No Of Pax" required>
            </div>
            <div class="col-lg-2">
                <label for="nationality_{{ $visa->id }}" class="form-label">Nationality</label>
                <input type="text" name="visa[{{ $visa->id }}][nationality]" value="{{ $visa->nationality }}" class="form-control" placeholder="Nationality" id="nationality_{{ $visa->id }}" autocomplete="off">
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Sale Cost {!!$required_check_sales_cost!!}</label>
                <input type="number" class="form-control visa_sale_cost" name="visa[{{ $visa->id }}][sale_cost]" value="{{ $visa->sale_cost }}" placeholder="Sale Cost" step="0.01" {!!$filed_condition_sales_cost!!}>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control visa_net_cost" name="visa[{{ $visa->id }}][net_cost]" value="{{ $visa->net_cost }}" placeholder="Net Cost" step="0.01" required="">
            </div>
            <div class="col-lg-2 col-md-6">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="visa_supplier select2" id="visaSupplier_{{ $visa->id }}" name="visa[{{ $visa->id }}][supplier]" data-placeholder="select visa supplier" required>
                    @foreach ($vendors as $v_key => $vendor)
                        <option value="{{ $vendor->name }}" {{ ($vendor->name == $visa->supplier) ? "selected" : "" }}> {{$vendor->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Remarks</label>
                <input type="text" class="form-control remarks" name="visa[{{ $visa->id }}][remarks]" value="{{ $visa->remarks }}" placeholder="Remarks">
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <div class="col-lg-4 col-md-6 mb-3">
                    <input type="hidden" name="visa[{{ $visa->id }}][id]" value="{{ $visa->id }}">
                    <button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row('visaInfo_{{ $visa->id }}', {{ $visa->id }})"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    <div class="modal-footer">
        <input type="hidden" name="visa_count" id="visa_details_count" value="{{ $booking->visas->count()+1}}">
        <input type="hidden" name="booking_id" id="booking_id" value="{{ $booking->id }}">
        <a href="javascript:void(0)" class="btn btn-warning" id="addHotel" onclick="add_visa_rows({{ $booking->is_full_package }})"><i class="ri-shield-user-fill me-1 align-middle"></i>Add</a>
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

            // initializeSelect2WithAjax('.airports', '{{ route('crm.get-airports') }}', 'Search for airports', '.modal');
            // initializeSelect2WithAjax('.visa_category', '{{ route('crm.get-visa-categories') }}', 'Visa Category');
            // initializeSelect2WithAjax('.visa_country', '{{ route('crm.get-countries') }}', 'Visa Country');
            // initializeSelect2WithAjax('.visa_supplier', '{{ route('crm.get-vendors') }}', 'Search for Vendor');
        });

    })
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });



    function add_visa_rows(isFullPackage) {

        //$('.select').select2();

        visa_count = $('#visa_details_count').val();

        $("#visaInfo_" + visa_count + " select").select2('destroy');

        const $visaContainer = $('#visa_details');

        // Determine readonly and required status based on `isFullPackage`
        let fieldCondition = isFullPackage ? 'readonly' : 'required';
        let requiredCheck = isFullPackage ? '' : '<span class="text-danger"> *</span>';

        // Create a div for each date
        const $div = $(`<div class="row clearfix visa_info mb-3" id="visaInfo_${visa_count}"></div>`);

        // Create date input field
        const $dateInput = $(`
            <div class="col-lg-2 col-md-6">
                <label>Visa Category<span class="text-danger"> *</span></label>
                <select class="visa_category select2" id="visaCategory_${visa_count}" name="visa[${visa_count}][visa_category]" data-placeholder="select visa visa category" required>
                    @foreach ($visaCategories as $a_key => $visa_category)
                        <option value="{{ $visa_category->name }}"> {{$visa_category->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label>Visa Country<span class="text-danger"> *</span></label>
                <select class="visa_country select2" id="visaCountry_${visa_count}" name="visa[${visa_count}][visa_country]" data-placeholder="select visa visa country" required>
                    @foreach ($countries as $c_key => $country)
                        <option value="{{ $country->name }}"> {{$country->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Number of Pax<span class="text-danger"> *</span></label>
                <input type="number" class="form-control no_of_pax" id="noOfPax_${visa_count}" name="visa[${visa_count}][no_of_pax]" value="" placeholder="No Of Pax" required>
            </div>
            <div class="col-lg-2">
                <label for="nationality_${visa_count}" class="form-label">Nationality</label>
                <input type="text" name="visa[${visa_count}][nationality]" value="" class="form-control" placeholder="Nationality" id="nationality_${visa_count}" autocomplete="off">
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Sale Cost ${requiredCheck}</label>
                <input type="number" class="form-control visa_sale_cost" name="visa[${visa_count}][sale_cost]" value="" placeholder="Sale Cost" step="0.01" ${fieldCondition}>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Net Cost<span class="text-danger"> *</span></label>
                <input type="number" class="form-control visa_net_cost" name="visa[${visa_count}][net_cost]" value="" placeholder="Net Cost" step="0.01" required="">
            </div>
            <div class="col-lg-2 col-md-6">
                <label>Supplier<span class="text-danger"> *</span></label>
                <select class="visa_supplier select2" id="visaSupplier_${visa_count}" name="visa[${visa_count}][supplier]" data-placeholder="select visa supplier" required>
                    @foreach ($vendors as $v_key => $vendor)
                        <option value="{{ $vendor->name }}"> {{$vendor->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <label>Remarks</label>
                <input type="text" class="form-control remarks" name="visa[${visa_count}][remarks]" value="" placeholder="Remarks">
            </div>
            <div class="col-lg-2 col-md-6 mb-3">
                <div class="col-lg-4 col-md-6 mb-3">
                    <input type="hidden" name="visa[${visa_count}][id]" value="">
                    <button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(visaInfo_${visa_count}, 'visa')"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
                </div>
            </div>
        `);

        $div.append($dateInput);
        // Append the div to the container
        $visaContainer.append($div);

        $("#visaInfo_" + visa_count + " .select2").select2({
            dropdownParent: $('#extraLargeModal')
        });

        $('#visa_details_count').val(parseInt(visa_count) + +1);
        // initializeSelect2WithAjax('#visaCategory_' + visa_count, '{{ route('crm.get-visa-categories') }}', 'Visa Category');
        // initializeSelect2WithAjax('#visaCountry_' + visa_count, '{{ route('crm.get-countries') }}', 'Visa Country');
        // initializeSelect2WithAjax('#visaSupplier_' + visa_count, '{{ route('crm.get-vendors') }}', 'Search for Vendor');

    };


    function remove_row(row_id, data_id = null) {

        var $row = $(row_id);
        //console.log(row_id, data_id);
        var visa_id = data_id;
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
                        url: '{{ route("crm.delete-visa-details", "") }}/' + visa_id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
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
    $('#updateVisaDetails').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{ route('crm.update-visa-details') }}",
            data: $(this).serialize(),
            success: function(response) {
                // console.log(response);

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

                Swal.fire({
                    title: 'Error!',
                    icon: 'error',
                    text: xhr.responseJSON.message,
                });
            }
        });
        return;
    });
</script>
