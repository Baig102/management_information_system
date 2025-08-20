@extends('layouts.master-crm')
@section('title')
    @lang('translation.crm')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboards
        @endslot
        @slot('title')
            Pool Inquiry
        @endslot
    @endcomponent

@section('css')
    <style>
        .tab-pane.fade {
            transition: all 0.2s;
            transform: translateY(1rem);
        }

        .tab-pane.fade.show {
            transform: translateY(0rem);
        }

        .select2.selection {}
    </style>
@endsection

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex p-2">
                <h4 class="card-title mb-0 flex-grow-1">Pool Inquiry Search</h4>
            </div><!-- end card header -->
            <form action="{{ route('crm.pool-inquiry-list') }}" method="get" id="inquirySearchForm" enctype="multipart/form-data">
                @csrf
                <div class="card-body bg-light">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="company" class="form-label">Select Company</label>
                            <select class="select2 form-control-sm" id="company" name="company_id" data-placeholder="Select Company">
                                <option></option>
                                @foreach ($assignedCompanies as $assiCompany)
                                    <option value="{{ $assiCompany->id }}"
                                        {{ isset($searchParams['company_id']) && $searchParams['company_id'] == $assiCompany->id ? 'selected' : '' }}>
                                        {{ $assiCompany->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="{{ route('crm.pool-inquiry-list') }}" class="btn btn-danger"> <i class="ri-restart-line me-1 align-bottom"></i> Reset </a>
                        <button type="submit" class="btn btn-primary"> <i class="ri-equalizer-fill me-1 align-bottom"></i> Search </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@if (isset($inquiries))

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Pool Inquiry List</h4>
                    <button type="button" id="bulkAssignBtn" class="btn btn-soft-success btn-sm material-shadow-none" onclick="bulk_assign_inquiry()">
                        <i class="ri-file-list-3-line align-middle"></i> Bulk Assign
                    </button>
                </div><!-- end card header -->
                <div class="card-body">

                    <div class="table-responsive">
                        {{-- <table class="table align-middle mb-0 fs-12"> --}}
                        <table id="alternative-paginationn" class="alternative-paginationn table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12" data-ordering="false">
                        {{-- <table id="" class="table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12 table-sm" data-ordering="false"> --}}
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">Company & Source Details</th>
                                    <th scope="col">Passenger Details</th>
                                    <th scope="col">Flight Details</th>
                                    <th scope="col">Umrah Details</th>
                                    <th scope="col">Inquiry Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inquiries as $key => $inquiry)
                                    @php
                                        $phone_number   = "************";
                                        $email          = "************";

                                        if (Auth::user()->role < 3 || ($inquiry->inquiry_assignment_status == 2 && ( $inquiry->inquiry_assigned_to == Auth::id() || (Auth::user()->role == 4 && in_array($inquiry->inquiry_assigned_to, $agents->toArray())) ))
                                        ) {
                                            $phone_number = $inquiry->contact_number;
                                            $email = $inquiry->email;
                                        }
                                    @endphp

                                    <tr id="inquiryRow_{{ $inquiry->id }}">
                                        <td>
                                            <a href="javascript:void(0)" onclick="view_inquiry({{ $inquiry->id }})" class="fw-semibold me-2">
                                                {{ $inquiry->id }}
                                            </a>
                                        </td>

                                        <td>
                                            <p class="mb-0"><i class="ri-coupon-line align-middle text-muted fs-12 me-2"></i>Company: {{ $inquiry->company_name }}</p>
                                            <p class="mb-0"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Source:</span> {{ $inquiry->source }}</p>
                                            <p class="mb-0 text-warning"><i class="ri-calendar-todo-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Created At:</span> {{ date("M-d-Y", strtotime($inquiry->created_at)) }}</p>
                                            @if ($inquiry->inquiry_assigned_to != null)
                                                <hr>
                                                <p class="mb-0 text-primary"><i class="ri-message-3-line align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Recent Action:</span> {{ $inquiry->inquiryAssigments->recent_status ?? 'Pending' }}</p>
                                                <p class="mb-0 text-primary"><i class="ri-calendar-todo-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Recent Action On:</span> {{ ($inquiry->inquiryAssigments->recent_status_on != null) ? date("M-d-Y", strtotime($inquiry->inquiryAssigments->recent_status_on)) : '-' }}</p>
                                            @endif


                                        </td>

                                        <td>
                                            <span>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="fs-12 mb-1">
                                                            <a href="javascript:void(0)" class="link-primary">
                                                                {{ $inquiry->lead_passenger_name }}
                                                            </a>
                                                        </h5>

                                                        <p class="text-muted mb-0">
                                                            <a href="tel:{{ $phone_number }}" target="__blank" class="text-muted">
                                                                <span class="fw-medium"><i class="ri-phone-line me-2 align-middle text-muted fs-16"></i>{{ $phone_number }} </span>
                                                            </a>
                                                        </p>

                                                        <p class="text-muted mb-0">
                                                            <a href="mailto:{{ $email}}" class="text-muted" target="__blank">
                                                                <span class="fw-medium"><i class="ri-mail-line me-2 align-middle text-muted fs-16"></i>{{ $email}}</span>
                                                            </a>
                                                        </p>

                                                    </div>
                                                </div>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-5">
                                                            <h6 class="mb-2 text-primary">Departure Details</h6>
                                                            <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-takeoff-line"></i> {{$inquiry->departure_airport}}</p>
                                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i> {{$inquiry->departure_date}} </p>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <spain class="text-danger display-6"><i class="ri-plane-line"></i></spain>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <h6 class="mb-2 text-warning">Arrival Details</h6>
                                                            <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-land-line"></i> {{$inquiry->arrival_airport}}</p>
                                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i> {{$inquiry->arrival_date}} </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0"><i class="ri-coupon-line align-middle text-muted fs-12 me-2"></i>Nights In Makkah: {{ $inquiry->nights_in_makkah }}</p>
                                            <p class="mb-0"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Nights In Madina:</span> {{ $inquiry->nights_in_madina }}</p>
                                            <p class="mb-0"><i class=" ri-shield-user-line align-middle text-muted fs-12 me-2"></i><span class="fw-semibold"># Of Adult Travelers:</span> {{ $inquiry->no_of_adult_travelers }}</p>
                                            <p class="mb-0"><i class=" ri-shield-user-line align-middle text-muted fs-12 me-2"></i><span class="fw-semibold"># Of Child Travelers:</span> {{ $inquiry->no_of_child_travelers }}</p>
                                            <p class="mb-0"><i class=" ri-shield-user-line align-middle text-muted fs-12 me-2"></i><span class="fw-semibold"># Of Infant Travelers:</span> {{ $inquiry->no_of_infant_travelers }}</p>
                                        </td>
                                        <td>
                                            @if ($inquiry->inquiry_assignment_status == 2)
                                                <p class="mb-0"><i class="ri-coupon-line align-middle fs-12 me-2 "></i>Inquiry Status: <span class="{{ ($inquiry->inquiry_assignment_status == 1) ? "text-danger" : "text-success" }}">{{ ($inquiry->inquiry_assignment_status == 1) ? "Pending" : "Assigned" }}</span></p>
                                                <p class="mb-0"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Assignment On:</span> {{ ($inquiry->inquiry_assignment_status == 1) ? "Nill" : $inquiry->inquiry_assignment_on }}</p>
                                                <p class="mb-0 text-success"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Assigned To:</span> {{userDetails($inquiry->inquiry_assigned_to)->name}}</p>
                                                <p class="mb-0 text-info"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Assigned By:</span> {{ ($inquiry->inquiryAssigments->agent_id == $inquiry->inquiryAssigments->assigned_by) ? "Self Assigned": userDetails($inquiry->inquiryAssigments->assigned_by)->name }}</p>
                                            @else
                                                <p class="mb-0"><i class="ri-coupon-line align-middle fs-12 me-2 "></i>Inquiry Status: <span class="text-danger" id="inquiryStatus_{{ $inquiry->id }}">Pending</span></p>
                                            @endif

                                        </td>

                                        <td>
                                            @if (Auth::user()->role < 3)
                                                <div class="form-check form-switch form-switch-custom form-switch-secondary">
                                                    <input class="form-check-input inquiry-checkbox" type="checkbox" name="bulk_assign" value="{{ $inquiry->id }}" role="switch" id="bulkAssign_{{ $inquiry->id }}" data-company="{{ $inquiry->company_id }}" >
                                                </div>
                                            @endif
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-equalizer-fill"></i> </button>
                                                <ul class="dropdown-menu dropdown-menu-end">

                                                    <li><button type="button" class="dropdown-item text-primary" onclick="view_inquiry({{ $inquiry->id }})"><i class="ri-eye-fill align-bottom me-2"></i> View</button></li>

                                                    @if (Auth::user()->role < 3)

                                                    <li><button type="button" class="dropdown-item text-danger {{ ($inquiry->inquiry_assignment_status == 2) ? "" : "disabled" }}" id="ReAssignInquiry_{{ $inquiry->id }}" onclick="re_assign_inquiry({{ $inquiry->id }})"><i class="ri-reply-line align-bottom me-2"></i> Assign Inquiry</button></li>

                                                    @endif

                                                    <li><button type="button" class="dropdown-item text-success {{ ($inquiry->inquiry_assignment_status == 2) ? "" : "" }}" id="pickupInquiry_{{ $inquiry->id }}" onclick="pickup_inquiry({{ $inquiry->id }})"><i class="ri-shield-star-line align-bottom me-2"></i> Pickup</button></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center"> No Record Found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- end table -->
                        <!-- Pagination Links -->
                        {{ $inquiries->links('pagination::bootstrap-5') }}
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="selected_inquiries" id="selectedInquiries" value="">
@endif
@endsection
@section('script')
<script>
    $(function() {
        $('select.select2').select2();
        $(".flatpickr-date").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $('#alternative-paginationn').dataTable( {
            "pageLength": 100,
            "bPaginate": false,
        } );

    })


    function pickup_inquiry(inquiry_id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Once assigned, you won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Pickup Inquiry!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to approve the refund
                 $.ajax({
                    url: '{{ route("crm.save-pool-assign-inquiry", "") }}/' + inquiry_id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // console.log(response);

                        Swal.fire(
                            'Approved!',
                            response.message,
                            'success'
                        );
                        $('#pickupInquiry_'+inquiry_id).addClass('disabled');
                        //$('.modal.extraLargeModal').modal('toggle');
                        //$('.modal.fullscreeexampleModal').modal('toggle');
                        //view_booking(booking_id);
                        // Optionally, you can remove the deleted payment record from the DOM
                        //$('#payment_' + paymentId).remove();

                    },
                    error: function (response) {
                        //console.log(response.responseJSON.message);
                        //console.log(JSON.parse(response));
                        Swal.fire(
                            'Error!',
                            response.responseJSON.message,
                            //'There was a problem in pickup inqury. Please try again...',
                            'error'
                        );
                    }
                });
            }
        });
    };

    function re_assign_inquiry(inquiry_id){
        $.ajax({
            type: 'GET',
            url: 're-assign-inquiry/' + inquiry_id, // in here you should put your query
            success: function (r) {

                $('.extraLargeModal .modal-content').html(r);
                $('.modal.extraLargeModal').modal('show');

            }
        });
    };

    function bulk_assign_inquiry(inquiry_id){
        // Clear any previous selections

        const inquiryIds = $('#selectedInquiries').val();
        if (inquiryIds) {

            //console.log(inquiryIds);
            // AJAX call to fetch the modal content
            $.ajax({
                type: 'GET',
                url: 'bulk-assign-inquiries/' + inquiryIds, // Pass the inquiry IDs in the URL
                success: function(response) {
                    $('.extraLargeModal .modal-content').html(response);
                    $('.modal.extraLargeModal').modal('show');

                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error',
                        icon: 'error',
                        text: 'An error occurred while fetching data',
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'Warning',
                icon: 'warning',
                text: 'Please select at least one inquiry.',
            });
        }
    };


</script>
@endsection
