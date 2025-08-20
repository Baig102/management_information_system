<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title">Update Passenger Details</h5>
</div>
<form id="updatePassengers">
    <div class="modal-body" id="passenger_details">
        {{-- <pre>
        {{ $passengers[0]->pnr_id != null ? "found" : "empty" }}
        </pre> --}}
        @csrf
        @foreach ($passengers as $pass_key => $passenger)

        {{-- <pre> {{ print_r($passenger) }} </pre> --}}
        <div class="row clearfix passenger_info mb-3" id="passengerInfo_{{ $passenger->id }}">
            @php
            $required_check = "required";
            $required_star = '<span class="text-danger"> *</span>';
            $dob = $passenger->date_of_birth ? \Carbon\Carbon::parse($passenger->date_of_birth)->format('d-m-Y') : '';
            @endphp
            @if ($pass_key > 1)

                <hr>
            @endif
                <div class="col-lg-2">
                    <label for="title_{{ $passenger->id }}" class="form-label">Title<span class="text-danger"> *</span></label>
                    <select class="select2 form-control-sm" id="title_{{ $passenger->id }}" name="passenger[{{ $passenger->id }}][title]" {{ $passengers[0]->pnr_id != null ? "" : "" }}>
                        <option></option>
                        <option value="Mr" {{ ($passenger->title == 'Mr') ? "selected" : "" }}>Mr</option>
                        <option value="Ms" {{ ($passenger->title == 'Ms') ? "selected" : "" }}>Ms</option>
                        <option value="Mrs" {{ ($passenger->title == 'Mrs') ? "selected" : "" }}>Mrs</option>
                        <option value="Miss" {{ ($passenger->title == 'Miss') ? "selected" : "" }}>Miss</option>
                        <option value="Mstr" {{ ($passenger->title == 'Mstr') ? "selected" : "" }}>Mstr</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="first_name_{{ $passenger->id }}" class="form-label">First Name<span class="text-danger"> *</span></label>
                    <input type="text" name="passenger[{{ $passenger->id }}][first_name]" value="{{ $passenger->first_name }}" class="form-control" placeholder="First Name" id="first_name_{{ $passenger->id }}" autocomplete="off" {{ $passengers[0]->pnr_id != null ? "readonly" : "" }} required>
                </div>
                <div class="col-lg-2">
                    <label for="middle_name_{{ $passenger->id }}" class="form-label">Middle Name</label>
                    <input type="text" name="passenger[{{ $passenger->id }}][middle_name]" value="{{ $passenger->middle_name }}" class="form-control" placeholder="Middle Name" id="middle_name_{{ $passenger->id }}" {{ $passengers[0]->pnr_id != null ? "readonly" : "" }} autocomplete="off">
                </div>
                <div class="col-lg-2">
                    <label for="last_name_{{ $passenger->id }}" class="form-label">Last Name</label>
                    <input type="text" name="passenger[{{ $passenger->id }}][last_name]" value="{{ $passenger->last_name }}" class="form-control" placeholder="Last Name" id="last_name_{{ $passenger->id }}" {{ $passengers[0]->pnr_id != null ? "readonly" : "" }} autocomplete="off">
                </div>
                <div class="col-lg-2">
                    <label for="date_of_birth_{{ $passenger->id }}" class="form-label">Date of Birth</label>
                    <input type="text" name="passenger[{{ $passenger->id }}][date_of_birth]" value="{{ $dob }}" class="form-control cleave-date" placeholder="DD-MM-YYYY" id="date_of_birth_{{ $passenger->id }}" autocomplete="off">
                </div>
                <div class="col-lg-2">
                    <label for="nationality_{{ $passenger->id }}" class="form-label">Nationality</label>
                    <input type="text" name="passenger[{{ $passenger->id }}][nationality]" value="{{ $passenger->nationality }}" class="form-control" placeholder="Nationality" id="nationality_{{ $passenger->id }}" autocomplete="off">
                </div>
                <div class="col-lg-2 mt-2">
                    <label for="mobile_number_{{ $passenger->id }}" class="form-label">Mobile Number{!! ($pass_key == 0) ? '<span class="text-danger"> *</span>' : "" !!}</label>
                    <input type="text" name="passenger[{{ $passenger->id }}][mobile_number]" value="{{ $passenger->mobile_number }}" class="form-control" placeholder="Mobile Number" id="mobile_number_{{ $passenger->id }}" autocomplete="off" {{ ($pass_key == 0) ? "required" : "" }}>
                </div>
                <div class="col-lg-2 mt-2">
                    <label for="email_{{ $passenger->id }}" class="form-label">Email {!! ($pass_key == 0) ? '<span class="text-danger"> *</span>' : "" !!}</label>
                    <input type="text" name="passenger[{{ $passenger->id }}][email]" value="{{ $passenger->email }}" class="form-control chose_multi" placeholder="Email" id="email_{{ $passenger->id }}" data-choices data-choices-limit="10" data-choices-removeItem autocomplete="off" {{ ($pass_key == 0) ? "required" : "" }}>
                </div>
                <div class="col-lg-2 mt-2">
                    <label for="address_{{ $passenger->id }}" class="form-label">Address{!! ($pass_key == 0) ? '<span class="text-danger"> *</span>' : "" !!}</label>
                    <input type="text" name="passenger[{{ $passenger->id }}][address]" value="{{ $passenger->address }}" class="form-control" placeholder="Address" id="address_{{ $passenger->id }}" autocomplete="off" {{ ($pass_key == 0) ? "required" : "" }}>
                </div>
                <div class="col-lg-2 mt-2">
                    <label for="post_code_{{ $passenger->id }}" class="form-label">Post Code</label>
                    <input type="text" name="passenger[{{ $passenger->id }}][post_code]" value="{{ $passenger->post_code }}" class="form-control" placeholder="Post Code" id="post_code_{{ $passenger->id }}" autocomplete="off">
                </div>
                <div class="col-lg-2 mt-2">
                    <label for="ticket_number_{{ $passenger->id }}" class="form-label">Ticket Number</label>
                    <input type="text" name="passenger[{{ $passenger->id }}][ticket_number]" value="{{ $passenger->ticket_number }}" class="form-control" placeholder="Ticket Number" id="ticket_number_{{ $passenger->id }}" autocomplete="off">
                </div>
                <div class="col-lg-2 mt-2">
                    <label for="pnr_code_{{ $passenger->id }}" class="form-label">PNR Code</label>
                    <input type="text" name="passenger[{{ $passenger->id }}][pnr_code]" value="{{ $passenger->pnr_code }}" class="form-control" placeholder="PNR Code" id="pnr_code_{{ $passenger->id }}" autocomplete="off">
                </div>
                {{-- <div class="col-lg-2 mt-3 {{ $passengers[0]->pnr_id != null ? "d-none" : "" }}"> --}}
                <div class="col-lg-2 mt-3 {{ $passenger->pnr_id != null ? "d-none" : "" }}">
                    <input type="hidden" name="passenger[{{ $passenger->id }}][id]" value="{{ $passenger->id }}">
                    <button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4"  onclick="remove_row('passengerInfo_{{ $passenger->id }}', {{ $passenger->id }})"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
                </div>
                <hr class="mt-4">

        </div>
        @endforeach
    </div>


<div class="modal-footer">
    <input type="hidden" name="passenger_count" id="passenger_details_count" value="{{ $passengers->count()+1}}">
    <input type="hidden" name="booking_id" id="booking_id" value="{{ $booking_id }}">
    @if ($passengers[0]->pnr_id == null)
    @endif
        <a href="javascript:void(0)" class="btn btn-warning" id="addPassenger" onclick="add_passenger_rows()"><i class="ri-shield-user-fill me-1 align-middle"></i>Add</a>
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

    function add_passenger_rows() {
        pass_count = $('#passenger_details_count').val();

        const $passengerDetails = $('#passenger_details');
        //$passengerDetails.empty();

        if (pass_count == 1) {
            var filed_condition = 'required';
            var required_check = '<span class="text-danger"> *</span>';
        } else {
            var filed_condition = '';
            var required_check = '';
        }

        // Create a div for each date
        const $div = $(`<div class="row clearfix passenger_info mb-3" id="passengerInfo_${pass_count}"> <input type="hidden" name="passenger[${pass_count}][id]" value=""> </div>`);

        // Create date input field
        const $dateInput = $(`
            <div class="col-lg-2">
                <label for="title_${pass_count}" class="form-label">Title<span class="text-danger"> *</span></label>
                <select class="select2 form-control-sm" id="title_${pass_count}" name="passenger[${pass_count}][title]">
                    <option></option>
                    <option value="Mr">Mr</option>
                    <option value="Ms">Ms</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Miss">Miss</option>
                    <option value="Mstr">Mstr</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="first_name_${pass_count}" class="form-label">First Name<span class="text-danger"> *</span></label>
                <input type="text" name="passenger[${pass_count}][first_name]" value="" class="form-control" placeholder="First Name" id="first_name_${pass_count}" autocomplete="off" required>
            </div>
            <div class="col-lg-2">
                <label for="middle_name_${pass_count}" class="form-label">Middle Name</label>
                <input type="text" name="passenger[${pass_count}][middle_name]" value="" class="form-control" placeholder="Middle Name" id="middle_name_${pass_count}" autocomplete="off">
            </div>
            <div class="col-lg-2">
                <label for="last_name_${pass_count}" class="form-label">Last Name</label>
                <input type="text" name="passenger[${pass_count}][last_name]" value="" class="form-control" placeholder="Last Name" id="last_name_${pass_count}" autocomplete="off">
            </div>
            <div class="col-lg-2">
                <label for="date_of_birth_${pass_count}" class="form-label">Date of Birth</label>
                <input type="text" name="passenger[${pass_count}][date_of_birth]" value="" class="form-control cleave-date" placeholder="DD-MM-YYYY" id="date_of_birth_${pass_count}" autocomplete="off">
            </div>
            <div class="col-lg-2">
                <label for="nationality_${pass_count}" class="form-label">Nationality</label>
                <input type="text" name="passenger[${pass_count}][nationality]" value="" class="form-control" placeholder="Nationality" id="nationality_${pass_count}" autocomplete="off">
            </div>
            <div class="col-lg-2 mt-2">
                <label for="mobile_number_${pass_count}" class="form-label">Mobile Number${required_check}</label>
                <input type="text" name="passenger[${pass_count}][mobile_number]" value="" class="form-control chose_multi" placeholder="Mobile Number" id="mobile_number_${pass_count}" autocomplete="off" ${filed_condition}>
            </div>
            <div class="col-lg-2 mt-2">
                <label for="email_${pass_count}" class="form-label">Email${required_check}</label>
                <input type="text" name="passenger[${pass_count}][email]" value="" class="form-control chose_multi" placeholder="Email" id="email_${pass_count}" data-choices data-choices-limit="10" data-choices-removeItem autocomplete="off" ${filed_condition}>
            </div>
            <div class="col-lg-2 mt-2">
                <label for="address_${pass_count}" class="form-label">Address${required_check}</label>
                <input type="text" name="passenger[${pass_count}][address]" value="" class="form-control" placeholder="Address" id="address_${pass_count}" autocomplete="off" ${filed_condition}>
            </div>
            <div class="col-lg-2 mt-2">
                <label for="post_code_${pass_count}" class="form-label">Post Code</label>
                <input type="text" name="passenger[${pass_count}][post_code]" value="" class="form-control" placeholder="Post Code" id="post_code_${pass_count}" autocomplete="off">
            </div>
            <div class="col-lg-2 mt-2">
                <label for="ticket_number_${pass_count}" class="form-label">Ticket Number</label>
                <input type="text" name="passenger[${pass_count}][ticket_number]" value="" class="form-control" placeholder="Ticket Number" id="ticket_number_${pass_count}" autocomplete="off">
            </div>
            <div class="col-lg-2 mt-2 d-none">
                <label for="pnr_code_${pass_count}" class="form-label">PNR Code</label>
                <input type="number" name="passenger[${pass_count}][pnr_code]" value="" class="form-control" placeholder="PNR Code" id="pnr_code_${pass_count}" autocomplete="off">
            </div>
            <div class="col-lg-2 mt-3">
                <button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(passengerInfo_${pass_count})"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
            </div>
            <hr class="mt-4">
        `);

        $div.append($dateInput);

        // Append the div to the container
        $passengerDetails.append($div);
        var cleaveDate = new Cleave('#date_of_birth_'+pass_count, {
            date: true,
            delimiter: '-',
            datePattern: ['d', 'm', 'Y']
        });

        $("#title_"+pass_count).select2();


        $('#passenger_details_count').val(parseInt(pass_count) + +1);
    };

    function remove_row(row_id, data_id=null) {

        var $row = $(row_id);
        //console.log(row_id, data_id);
        var passenger_id = data_id;
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
                        url: '{{ route("crm.delete-passenger", "") }}/' + passenger_id,
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
    $('#updatePassengers').submit(function(event) {

        //console.log($(this).serialize());
        // Perform custom validation here

        //var balance_amount = parseFloat($('[name="balance_amount"]').val());
        var booking_id = $('[name="booking_id"]').val();

        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: "{{ route('crm.update-passenger') }}",
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
