<div class="modal-header border-bottom border-2 py-2 bg-soft-info">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Booking Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @php
        // Convert the refunds array to a collection
        $refundsCollection = collect($booking->refunds);
        // Filter the collection where refund_status is 1
        $filteredRefunds = $refundsCollection->where('refund_status', 1);
        // Calculate the total refunded_amount
        $totalRefundedAmount = $filteredRefunds->sum('refunded_amount');
        // Calculate the total service_charges
        $totalServiceCharges = $filteredRefunds->sum('service_charges');

    @endphp
    @if ($booking->refunded_amount > 0)
        <h1 class="position-fixed start-50 translate-middle opacity-75 text-danger text-uppercase shadow-lg rounded display-1" style="rotate: -45deg; z-index: 99999;"> {{ ($booking->deposite_amount > ($totalRefundedAmount + $totalServiceCharges)) ? "Partial Refunded" : (($booking->deposite_amount == ($totalRefundedAmount + $totalServiceCharges)) ? "Fully Refunded" : "") }} </h1> {{-- top-50 position-absolute --}}
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex bg-success">
                    <h4 class="card-title mb-0 flex-grow-1">Booking Stage</h4>
                </div>

                <div class="card-body">

                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="progress progress-step-arrow progress-info">
                                <a href="javascript:void(0);" class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Stages 1</a>
                                <a href="javascript:void(0);" class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"> Stages 2</a>
                                <a href="javascript:void(0);" class="progress-bar bg-light text-body" role="progressbar" style="width: 100%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"> Stages 3</a>
                                <a href="javascript:void(0);" class="progress-bar bg-light text-body" role="progressbar" style="width: 100%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"> Stages 4</a>
                                <a href="javascript:void(0);" class="progress-bar bg-light text-body" role="progressbar" style="width: 100%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"> Stages 5</a>
                            </div>
                        </div><!-- end col -->

                    </div><!-- end row -->

                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
    </div>

    <div class="row">
        <div class="col lg-12">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-body">
                    <h4 class="card-title mb-0 flex-grow-1">Details of Booking # {{ $booking->booking_prefix }} {{ $booking->booking_number }}</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('crm.view-booking-invoice', ['id' => $booking->id, 'preview_type' => 'v2']) }}" target="_blank" class="btn btn-soft-danger btn-sm"> Print Invoice V2 </a>
                        <a href="{{ route('crm.generate-booking-invoice', $booking->id) }}" target="_blank" class="btn btn-soft-secondary btn-sm"> Export Booking </a>
                        {{-- <button type="button" class="btn {{ ($booking->ticket_status == 1 ) ? "btn-soft-danger" : (($booking->ticket_status == 2 ) ? "btn-soft-primary" : "btn-soft-success"); }} btn-sm" onclick="update_ticket_status({{ $booking->id }})">Ticket: {{ $ticket_status->name }} </button> --}}
                        <button type="button" class="btn {{ ($booking->ticket_status == 1 ) ? "btn-soft-danger" : (($booking->ticket_status == 2 ) ? "btn-soft-primary" : "btn-soft-success"); }} btn-sm" onclick="update_ticket_status({{ $booking->id }})">{{ $ticket_status->details }} </button>
                        <button type="button" class="btn btn-soft-primary btn-sm {{ ($booking->booking_status == 1 ) ? "btn-soft-success" : (($booking->booking_status == 2 ) ? "btn-soft-warning" : "btn-soft-danger"); }}" onclick="update_booking_status({{ $booking->id }})">
                            {{ $booking_status->name }}
                        </button>
                    </div>
                </div>

                <div class="card-body d-none">

                    <div class="row g-0 text-center">
                        <div class="col-lg-4 col-sm-2 bg-danger h-100">
                            <div class="p-3 border border-dashed border-start-0">
                                {{-- <h5 class="mb-1">
                                        <span class="counter-value" data-target="854">854</span>
                                        <span class="text-success ms-1 fs-12">49%
                                            <i class="ri-arrow-right-up-line ms-1 align-middle"></i>
                                        </span>
                                    </h5> --}}
                                <h6 class="text-dark mb-1">Company: {{ $booking->company->name }} </h6>
                                <h6 class="text-dark mb-1">Booking #:
                                    {{ $booking->booking_prefix }}{{ $booking->booking_number }} </h6>
                                <p class="text-dark mb-0">Booking Date:
                                    {{ date('M-d-Y', strtotime($booking->booking_date)) }}</p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-lg-4 col-sm-2 bg-success h-100"  onclick="update_ticket_status({{ $booking->id }})">
                            <div class="p-3 border border-dashed border-start-0">
                                {{-- <h5 class="mb-1"><span class="counter-value" data-target="1278">1,278</span>
                                    <span class="text-success ms-1 fs-12">60%<i class="ri-arrow-right-up-line ms-1 align-middle"></i></span>
                                </h5>
                                <p class="text-muted mb-0">Conversion Rate</p> --}}
                               {{--  <h6 class="text-dark mb-1">Ticket Status: {{ $booking->ticket_status == 1 ? 'Pending' : 'Generated' }} </h6> --}}
                                <h6 class="text-dark mb-1">Ticket Status: {{ $ticket_status->name }} </h6>
                                <h6 class="text-dark mb-1">
                                    @php
                                        $ticketDeadline = Carbon\Carbon::parse($booking->ticket_deadline);
                                        $now = Carbon\Carbon::now();
                                    @endphp

                                    @if ($ticketDeadline->lessThan($now->addWeek()))
                                        @if ($ticketDeadline->lessThan($now))
                                            <div class="external-event fc-event bg-danger-subtle text-danger" data-class="bg-danger-subtle">
                                                <i class="ri-error-warning-line me-2"></i>Ticket Deadline: {{ date('M-d-Y', strtotime($ticketDeadline)) }}
                                            </div>
                                        @else
                                            <div class="external-event fc-event bg-warning-subtle text-warning" data-class="bg-warning-subtle">
                                                <i class="ri-alert-line me-2"></i>Ticket Deadline: {{ date('M-d-Y', strtotime($ticketDeadline)) }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="external-event fc-event bg-success-subtle text-success" data-class="bg-success-subtle">
                                            <i class="ri-checkbox-line me-2"></i>Ticket Deadline: {{ date('M-d-Y', strtotime($ticketDeadline)) }}
                                        </div>
                                    @endif


                                </h6>
                                <p class="text-dark mb-0">Payment Type:
                                    {{ $booking->payment_type == 1 ? 'Full Payment' : 'Installment' }} </p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-lg-4 col-sm-2 bg-warning h-100">
                            <div class="p-3 border border-dashed border-start-0 border-end-0">
                                {{-- <h5 class="mb-1"><span class="counter-value" data-target="3">3</span>m
                                    <span class="counter-value" data-target="40">40</span>sec
                                    <span class="text-success ms-1 fs-12">37%<i class="ri-arrow-right-up-line ms-1 align-middle"></i></span>
                                </h5>
                                <p class="text-muted mb-0">Avg. Session Duration</p> --}}
                                <h6 class="mb-1 text-dark">Booking Type:
                                    {{ $booking->booking_payment_term == 1 ? 'Non Refundable' : 'Refundable' }} </h6>
                                <h6 class="mb-1 text-dark">Trip Type:
                                    {{ $booking->trip_type == 1 ? 'One Way' : 'Return' }} </h6>
                                <p class="text-dark mb-0">Flight Type:
                                    {{ $booking->flight_type == 1 ? 'Direct' : 'In-Direct' }}</p>
                            </div>
                        </div>
                        <!--end col-->
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
    </div>

    {{-- Booking Information --}}
    <div class="">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex bg-primary">
                    <h4 class="card-title mb-0 flex-grow-1">Booking Information</h4>
                    <button type="button" onclick="edit_booking_information({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Update Booking Information </button>
                </div>

                <div class="card-body">

                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="table-responsive table-card fs-14">
                                <table class="table table-nowrap table-striped-columns mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Company</th>
                                            <th scope="col">Business Customer</th>
                                            <th scope="col">Booking Number</th>
                                            <th scope="col">Booking Date</th>
                                            <th scope="col">Ticket Status</th>
                                            <th scope="col">Ticket Deadline</th>
                                            <th scope="col">Payment Type</th>
                                            <th scope="col">Booking Type</th>
                                            <th scope="col">Trip Type</th>
                                            <th scope="col">Flight Type</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        <tr>
                                            <td><a href="javascript:void(0)" class="fw-semibold"> {{ $booking->company->name }} </a></td>
                                            <td>{{ $booking->businessCustomer->name ?? 'N/A' }}</td>
                                            <td><a href="javascript:void(0)" class="fw-semibold"> {{ $booking->booking_prefix }} {{ $booking->booking_number }}  </a></td>
                                            <td>{{ date('M-d-Y', strtotime($booking->booking_date)) }}</td>
                                            <td>{{ $ticket_status->name }}</td>
                                            <td>
                                                @if ($booking->ticket_deadline == null)
                                                    <div class="external-event fc-event bg-warning-subtle text-warning" data-class="bg-warning-subtle">
                                                        <i class="ri-alert-line me-2"></i>Ticket Deadline: Null
                                                    </div>
                                                @else
                                                    @php
                                                        $ticketDeadline = Carbon\Carbon::parse($booking->ticket_deadline);
                                                        $now = Carbon\Carbon::now();
                                                    @endphp
                                                    @if ($ticketDeadline->lessThan($now->addWeek()))
                                                        @if ($ticketDeadline->lessThan($now))
                                                            <div class="external-event fc-event bg-danger-subtle text-danger" data-class="bg-danger-subtle">
                                                                <i class="ri-error-warning-line me-2"></i>Ticket Deadline: {{ date('M-d-Y', strtotime($ticketDeadline)) }}
                                                            </div>
                                                        @else
                                                            <div class="external-event fc-event bg-warning-subtle text-warning" data-class="bg-warning-subtle">
                                                                <i class="ri-alert-line me-2"></i>Ticket Deadline: {{ date('M-d-Y', strtotime($ticketDeadline)) }}
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="external-event fc-event bg-success-subtle text-success" data-class="bg-success-subtle">
                                                            <i class="ri-checkbox-line me-2"></i>Ticket Deadline: {{ date('M-d-Y', strtotime($ticketDeadline)) }}
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $booking->payment_type == 1 ? 'Full Payment' : 'Installment' }}</td>
                                            <td>{{ $booking->booking_payment_term == 1 ? 'Non Refundable' : 'Refundable' }}</td>
                                            <td>{{ $booking->trip_type == 1 ? 'One Way' : 'Return' }}</td>
                                            <td>{{ $booking->flight_type == 1 ? 'Direct' : 'In-Direct' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->

                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
    </div>
    {{-- pnr --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-danger">
                    <h4 class="card-title mb-0 flex-grow-1">PNR Details</h4>
                    <div>
                        @if(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152]))
                            <button type="button" onclick="add_flights_actual_net({{ $booking->id }})" class="btn btn-soft-success btn-sm"> Add Actual Net</button>
                        @endif
                        <button type="button" onclick="add_pnr({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Add PNR </button>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="table-responsive table-card fs-14">
                                <table class="table table-nowrap table-striped-columns mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Supplier</th>
                                            <th scope="col">PNR</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        @forelse ($booking->pnrs as $pnr_key => $pnr)
                                        <tr id="pnr_{{$pnr_key+1}}">
                                            <td><a href="javascript:void(0)" class="fw-semibold"> {{$pnr_key+1}} </a></td>
                                            <td>{{$pnr->supplier}}</td>
                                            <td>{{$pnr->pnr}}</td>
                                            <td>
                                                <button type="button" onclick="delete_pnr({{$pnr->id}})" class="btn btn-sm btn-outline-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-fill fs-6"></i></button>
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3">No Record Found</td>
                                        </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->

                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
    </div>

    <div class="row">

        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-light">
                    <h4 class="card-title mb-0 flex-grow-1">Passenger Details</h4>
                    <button type="button" onclick="edit_passenger({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Update Details </button>
                </div>

                <div class="card-body">

                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="table-responsive table-card fs-14">
                                <table class="table table-nowrap table-striped-columns mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Ticket #</th>
                                            <th scope="col">PNR</th>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">Date Of Birth</th>
                                            <th scope="col">Age</th>
                                            <th scope="col">Nationality</th>
                                            <th scope="col">Mobile Number</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Address</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        @foreach ($booking->passengers as $pass_key => $passenger)
                                        <tr id="payment_{{$pass_key+1}}">
                                            <td><a href="javascript:void(0)" class="fw-semibold"> {{$pass_key+1}} </a></td>
                                            <td><a href="javascript:void(0)" class="fw-semibold"> {{$passenger->ticket_number}} </a></td>
                                            <td>{{$passenger->pnr_code}}</td>
                                            <td>{{ $passenger->title.' '.$passenger->first_name.' '.$passenger->middle_name.' '.$passenger->last_name }}</td>
                                            <td>{{ ($passenger->date_of_birth != null) ? date('M-d-Y', strtotime($passenger->date_of_birth)) : "-" }}</td>
                                            <td>{{ ($passenger->age != null) ? $passenger->age.' Years' : "-" }} </td>
                                            <td>{{ $passenger->nationality }}</td>
                                            <td><a href="tel:{{ $passenger->mobile_number }}">{{ $passenger->mobile_number }}</a></td>
                                            <td><a href="mailto:{{ $passenger->email }}">{{ $passenger->email }}</a></td>
                                            <td>{{ $passenger->address.', ' }}{{ $passenger->post_code }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->

                </div><!-- end card body -->
            </div><!-- end card -->
        </div>

        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-info">
                    <h4 class="card-title mb-0 flex-grow-1">Hotel Details</h4>
                    <div>
                        @if(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152]))
                            <button type="button" onclick="add_hotels_actual_net({{ $booking->id }})" class="btn btn-soft-success btn-sm"> Add Actual Net</button>
                        @endif
                        {{-- <button type="button" onclick="add_hotels_refund({{ $booking->id }})" class="btn btn-soft-danger btn-sm"> Add Hotels Refund</button> --}}
                        <button type="button" onclick="edit_hotel_details({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Update Hotel Details </button>
                    </div>
                    {{-- <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="fw-semibold text-uppercase fs-12">Sort by:
                                </span><span class="text-muted">Today<i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Yesterday</a>
                                <a class="dropdown-item" href="#">Last 7 Days</a>
                                <a class="dropdown-item" href="#">Last 30 Days</a>
                                <a class="dropdown-item" href="#">This Month</a>
                                <a class="dropdown-item" href="#">Last Month</a>
                            </div>
                        </div>
                    </div> --}}
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                            <tbody>
                                @foreach ($booking->hotels as $hotel_key => $hotel)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                {{-- <div class="avatar-sm bg-light rounded p-1 me-2">
                                                <img src="{{asset('build/images/products/img-1.png')}}" alt="" class="img-fluid d-block">
                                            </div> --}}
                                                <div class="avatar-sm p-1 me-2">
                                                    <span class="avatar-title bg-info-subtle rounded fs-3">
                                                        <i class="bx bx-buildings text-info"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="fs-14 my-1"><a
                                                            href="javascript:void(0)"
                                                            class="text-reset">{{ $hotel->hotel_name }}</a></h5>
                                                    <span
                                                        class="text-muted">{{ date('M-d-Y', strtotime($hotel->check_in_date)) }}</span>
                                                    / <span
                                                        class="text-muted">{{ date('M-d-Y', strtotime($hotel->check_out_date)) }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $hotel->meal_type }}</h5>
                                            <span class="text-muted">Meal Type</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $hotel->room_type }}</h5>
                                            <span class="text-muted">Room Type</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $hotel->hotel_confirmation_number }}
                                            </h5>
                                            <span class="text-muted">HCN</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-danger"> {{ ($hotel->deadline != null) ? date("d-F-Y", strtotime($hotel->deadline)) : "Null" }}</h5>
                                            <span class="text-muted">Deadline</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-success">
                                                {{ $booking->currency }}{{ number_format($hotel->sale_cost, 2) }}</h5>
                                            <span class="text-muted">Sale Cost</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-danger">
                                                {{ $booking->currency }}{{ number_format($hotel->net_cost, 2) }}</h5>
                                            <span class="text-muted">Net Cost</span>
                                        </td>
                                        @if(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152]))
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-warning">
                                                {{ $booking->currency }}{{ number_format($hotel->actual_net_cost, 2) }}</h5>
                                            <span class="text-muted">Actual Net Cost</span>
                                        </td>
                                        @endif
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $hotel->supplier }}</h5>
                                            <span class="text-muted">Supplier</span>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-body">
                    <h4 class="card-title mb-0 flex-grow-1">Transport Details</h4>
                    <div>
                    @if(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152]))
                        <button type="button" onclick="add_transport_actual_net({{ $booking->id }})" class="btn btn-soft-success btn-sm"> Add Actual Net</button>
                    @endif
                    <button type="button" onclick="edit_transport_details({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Update Transport Details </button>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                            <tbody>
                                @foreach ($booking->transports as $transport_key => $transport)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                {{-- <div class="avatar-sm bg-light rounded p-1 me-2">
                                                <img src="{{asset('build/images/products/img-1.png')}}" alt="" class="img-fluid d-block">
                                            </div> --}}
                                                <div class="avatar-sm p-1 me-2">
                                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                        <i class="bx bx-car text-warning"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="fs-14 my-1"><a href="javascript:void(0)" class="text-reset">{{ $transport->transport_type }}</a></h5>
                                                    <span class="text-muted">{{ date('M-d-Y', strtotime($transport->transport_date)) }}</span><br>
                                                    <span class="text-muted">{{ $transport->time }}</span>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $transport->reference_no??'Pending' }}</h5>
                                            <span class="text-muted">Reference No</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $transport->airport }}</h5>
                                            <span class="text-muted">Airport</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $transport->location }}</h5>
                                            <span class="text-muted">Location</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $transport->car_type }}
                                            </h5>
                                            <span class="text-muted">Car Type</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-success">
                                                {{ $booking->currency }}{{ number_format($transport->sale_cost, 2) }}</h5>
                                            <span class="text-muted">Sale Cost</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-danger">
                                                {{ $booking->currency }}{{ number_format($transport->net_cost, 2) }}</h5>
                                            <span class="text-muted">Net Cost</span>
                                        </td>
                                        @if(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152]))
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-warning">
                                                {{ $booking->currency }}{{ number_format($transport->actual_net_cost, 2) }}</h5>
                                            <span class="text-muted">Actual Net Cost</span>
                                        </td>
                                        @endif
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $transport->supplier }}</h5>
                                            <span class="text-muted">Supplier</span>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header align-items-center d-flex bg-danger">
                    <h4 class="card-title mb-0 flex-grow-1">Visa Information</h4>
                    <div>
                        @if(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152]))
                            <button type="button" onclick="add_visa_actual_net({{ $booking->id }})" class="btn btn-soft-success btn-sm"> Add Actual Net</button>
                        @endif
                        <button type="button" onclick="edit_visa_details({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Update Visa Details </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                            <tbody>
                                @foreach ($booking->visas as $visa_key => $visa)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">

                                                <div class="avatar-sm p-1 me-2">
                                                    <span class="avatar-title bg-danger-subtle rounded fs-3">
                                                        <i class="ri-ancient-gate-line text-danger"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="fs-14 my-1"><a href="javascript:void(0)" class="text-reset">{{ $visa->visa_category }}</a></h5>
                                                    <span class="text-muted">Visa Category</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $visa->visa_country }}</h5>
                                            <span class="text-muted">Visa Country</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $visa->nationality }}</h5>
                                            <span class="text-muted">Nationality</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $visa->no_of_pax }}</h5>
                                            <span class="text-muted">No Of PAX</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $visa->remarks }}
                                            </h5>
                                            <span class="text-muted">Remarks</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-success">
                                                {{ $booking->currency }}{{ number_format($visa->sale_cost, 2) }}</h5>
                                            <span class="text-muted">Sale Cost</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-danger">
                                                {{ $booking->currency }}{{ number_format($visa->net_cost, 2) }}</h5>
                                            <span class="text-muted">Net Cost</span>
                                        </td>
                                        @if(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152]))
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-warning">
                                                {{ $booking->currency }}{{ number_format($visa->actual_net_cost, 2) }}</h5>
                                            <span class="text-muted">Actual Net Cost</span>
                                        </td>
                                        @endif
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $visa->supplier }}</h5>
                                            <span class="text-muted">Supplier</span>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end card -->
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-warning">  {{-- bg-body --}}
                    <h4 class="card-title mb-0 flex-grow-1">Flight Details | <i class="ri-flight-takeoff-line"></i>{{ $booking->trip_type == 1 ? 'One Way' : 'Return' }} -> <i class="ri-plane-line"></i>{{ $booking->flight_type == 1 ? 'Direct' : 'In-Direct' }} {{-- | Ticket Supplier : {{ ($booking->ticket_supplier != null) ? $booking->ticket_supplier : "Pending" }} --}}</h4>
                    <button type="button" onclick="edit_flight_details({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Update Details </button>
                </div><!-- end card header -->
                <div class="card-body">
                    @if ($booking->is_date_change == 1)
                        <h3 class="position-absolute start-50 translate-middle opacity-75 text-danger text-uppercase" style="top: 30%;"> Date Change Request Generated</h3>
                    @endif
                    @foreach ($booking->flights as $flight )
                        <div class="row border border-2 material-shadow mt-1 rounded p-3">
                            <div class="col-lg-5">
                                <h6 class="mb-2 text-primary">Departure Details</h6>
                                <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-takeoff-line"></i> {{$flight->departure_airport}}</p>
                                <p class="mb-0"><i class=" ri-send-plane-fill"></i> {{$flight->air_line_name}}</p>
                                <p class="mb-0"><i class="ri-calendar-todo-line"></i> {{ date("M-d-Y", strtotime($flight->departure_date))}} | <i class="ri-calendar-check-line"></i> {{$flight->departure_time}}</p>
                            </div>
                            <div class="col-lg-2 text-center">
                                <spain class="text-danger display-6"><i class="ri-plane-line"></i></spain>
                                <div>
                                    <p class="mb-2 text-uppercase fw-medium">Supplier:</p>
                                    <div class="badge bg-danger fs-12">{{$flight->supplier}}</div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <h6 class="mb-2 text-warning">Arrival Details</h6>
                                <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-land-line"></i> {{$flight->arrival_airport}}</p>
                                <p class="mb-0"><i class="ri-send-plane-fill"></i> {{$flight->air_line_name}}</p>
                                <p class="mb-0"><i class="ri-calendar-todo-line"></i> {{date("M-d-Y", strtotime($flight->arrival_date))}} | <i class="ri-calendar-check-line"></i> {{$flight->arrival_time}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="col-lg-6">
            <div class="card ">
                <div class="card-header align-items-center d-flex bg-light">
                    <h4 class="card-title mb-0 flex-grow-1">Installment Plan Details</h4>
                    @if ($booking->payment_type == 2)
                        @if ($booking->installmentPlan->count() <= 0)
                            <button type="button" onclick="add_installment_plan({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Add Plan</button>
                        @endif
                        <button type="button" onclick="edit_installment_plan({{ $booking->id }})" class="btn btn-soft-danger btn-sm ml-1"> Edit Plan</button>
                        @if (Auth::user()->role != 5)
                        @endif

                    @endif
                </div><!-- end card header -->
                <div class="card-body">
                    @if ($booking->payment_type == 1)
                        <!-- Warning Alert -->
                        <div class="alert alert-info alert-dismissible alert-additional fade show mb-0 material-shadow" role="alert">
                            <div class="alert-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="ri-alert-line fs-16 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading">Fully Paid Invoice</h5>
                                        <p class="mb-0">No Installment Plan Found. </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else

                        <div class="table-responsive table-card">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <tbody>
                                    @foreach ($booking->installmentPlan as $installment_key => $installment)
                                        <tr class="{{ $installment->due_date <= date("Y-m-d") && $installment->is_received == 0 ? 'table-danger' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm p-1 me-2">
                                                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                            <i class="bx bx-wallet text-warning"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h5 class="fs-14 my-1"><a href="javascript:void(0)" class="text-reset">{{ $installment->installment_number }}</a></h5>
                                                        <span class="text-muted">Installment #</span>

                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">{{ date('M-d-Y', strtotime($installment->due_date)) }}</h5>
                                                <span class="text-muted">Due Date</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">{{ $booking->currency.' '.number_format($installment->amount, 2) }}</h5>
                                                <span class="text-muted">Amount</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">
                                                    @if ($installment->is_received==1)
                                                        <span class="text-success">Received</span>
                                                    @else
                                                        <span class="text-danger">Pending</span>
                                                    @endif
                                                </h5>
                                                <span class="text-muted">Payment Status</span>
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="col-lg-6 d-none">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-danger">
                    <h4 class="card-title mb-0 flex-grow-1">Transport Details</h4>
                    <button type="button" onclick="" class="btn btn-soft-secondary btn-sm"> Update Details </button>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                            <tbody>
                                @foreach ($booking->transports as $transport_key => $transport)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                {{-- <div class="avatar-sm bg-light rounded p-1 me-2">
                                                <img src="{{asset('build/images/products/img-1.png')}}" alt="" class="img-fluid d-block">
                                            </div> --}}
                                                <div class="avatar-sm p-1 me-2">
                                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                        <i class="bx bx-car text-warning"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <h5 class="fs-14 my-1"><a href="javascript:void(0)" class="text-reset">{{ $transport->transport_type }}</a></h5>
                                                    <span class="text-muted">{{ $transport->time }}</span>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $transport->airport }}</h5>
                                            <span class="text-muted">Airport</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $transport->location }}</h5>
                                            <span class="text-muted">Location</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $transport->car_type }}
                                            </h5>
                                            <span class="text-muted">Car Type</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-success">
                                                {{ $booking->currency }}{{ number_format($transport->sale_cost,2) }}</h5>
                                            <span class="text-muted">Sale Cost</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal text-danger">
                                                {{ $booking->currency }}{{ number_format($transport->net_cost,2) }}</h5>
                                            <span class="text-muted">Net Cost</span>
                                        </td>
                                        <td>
                                            <h5 class="fs-14 my-1 fw-normal">{{ $transport->supplier }}</h5>
                                            <span class="text-muted">Supplier</span>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-dark">
                    <h4 class="card-title mb-0 flex-grow-1 text-light">Pricing Details </h4>
                    @if (Auth::user()->role <= 3)
                        <button type="button" onclick="edit_pricing({{$booking->id}})" class="btn btn-soft-secondary btn-sm"> Update Details </button>
                    @endif

                </div><!-- end card header -->
                <div class="card-body">

                    @if ($booking->is_full_package == 1)
                        <h1 class="position-absolute start-50 translate-middle opacity-75 text-danger text-uppercase display-5" style="rotate: -45deg; z-index: 99999; top: 30%;"> Full Package</h1> {{-- top-50 position-absolute --}}
                    @endif

                    @php

                        //$total_other_charges = $booking->otherCharges->sum('amount');
                        $total_other_charges = $booking->otherCharges->filter(function($charge) {
                            return $charge->charges_type !== 'CC Charges';  // Assuming 'cc' is the type of credit card charge
                        })->sum('amount');
                        $total_paid_other_charges = $booking->otherCharges->sum('reciving_amount');
                        $total_remaining_other_charges = $booking->otherCharges->sum('remaining_amount');
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Sale Cost</th>
                                    <th>Net Cost</th>
                                    <th>Quantity</th>
                                    <th>Total Sale Cost</th>
                                    <th>Agent Net Total</th>
                                    @if(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152]))
                                    <th>Actual Net Total</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($booking->prices as $flight )
                                    <tr class="{{ ($flight->booking_type == 'Other Charges') ? 'd-none' : '' }}">
                                        <th scope="row">{{$flight->booking_type}}</th>
                                        <td><span class="text-warning">{{ $booking->currency }}{{ number_format($flight->sale_cost, 2)}}</span></td>
                                        <td><span class="text-secondary">{{ $booking->currency }}{{ number_format($flight->net_cost, 2)}}</span></td>
                                        <td>{{$flight->quantity}}</td>
                                        <td><span class="text-success">{{ $booking->currency }}{{ number_format($flight->total, 2)}}</span></td>
                                        <td><span class="text-primary">{{ $booking->currency }}{{ number_format($flight->net_total, 2)}}</span></td>
                                        @if(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152]))
                                        {{-- <td><span class="text-danger">{{ $booking->currency }}{{ number_format($flight->actual_net_total, 2)}}</span></td> --}}
                                         <td>
                                            <span class="text-danger">
                                                {{ $booking->currency }}{{ number_format($flight->actual_net_total + ($flight->aviation_fee_total ?? 0), 2) }}
                                            </span>
                                            @if(!empty($flight->aviation_fee_total) && $flight->aviation_fee_total > 0)
                                                <br>
                                                <small class="text-muted">Aviation Fee: {{ $booking->currency }}{{ number_format($flight->aviation_fee_total, 2) }}</small>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                 @endforeach

                                 <tr>
                                    <th scope="row">Other Charges</th>
                                    <td><span class="text-warning">{{ $booking->currency }}{{ number_format($total_other_charges, 2)}}</span></td>
                                    <td><span class="text-secondary">{{ $booking->currency.'0.00' }}</span></td>
                                    <td>1</td>
                                    <td><span class="text-success">{{ $booking->currency }}{{ number_format($total_other_charges, 2)}}</span></td>
                                    <td><span class="text-primary">{{ $booking->currency.'0.00' }}</span></td>
                                    @if(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152]))
                                    <td><span class="text-danger">{{ $booking->currency.'0.00' }}</span></td>
                                    @endif
                                </tr>

                                <tr>
                                    <th scope="row" colspan="{{(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152])) ? '6' : '5'}}"><span class="float-end">Total Invoice Amount</span></th>
                                    <td><span class="text-success">{{ $booking->currency }}{{ number_format($booking->total_sales_cost+$total_other_charges, 2)}}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="{{(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152])) ? '6' : '5'}}"><span class="float-end">Net Price</span></th>
                                    <td><span class="text-primary">{{ $booking->currency }}{{ number_format($booking->total_net_cost, 2)}}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="{{(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152])) ? '6' : '5'}}"><span class="float-end">Margin</span></th>
                                    <td><span class="text-warning">{{ $booking->currency }}{{ number_format($booking->total_sales_cost+$total_other_charges-$booking->total_net_cost, 2)}}</span></td>
                                </tr>
                                @if(in_array(auth()->id(), [1,9,71,42,7,112,2,117,152]))
                                <tr>
                                    <th scope="row" colspan="6"><span class="float-end">Actual Margin</span></th>
                                    <td><span class="text-danger">{{ $booking->currency }}{{ number_format($booking->actual_margin, 2)}}</span></td>
                                </tr>
                                @endif

                                 {{-- <tr>
                                    <th scope="row" colspan="6"><span class="float-end">Total Invoice Amount</span></th>
                                    <td><span class="text-success">{{ $booking->currency }}{{ number_format($booking->total_sales_cost, 2)}}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="6"><span class="float-end">Net Price</span></th>
                                    <td><span class="text-primary">{{ $booking->currency }}{{ number_format($booking->total_net_cost, 2)}}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="6"><span class="float-end">Margin</span></th>
                                    <td><span class="text-warning">{{ $booking->currency }}{{ number_format($booking->margin, 2)}}</span></td>
                                </tr> --}}



                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-success">
                    <h4 class="card-title mb-0 flex-grow-1">Other Charges Details</h4>
                    {{-- <button type="button" onclick="" class="btn btn-soft-secondary btn-sm"> Update Details </button> --}}
                    <button type="button" onclick="add_other_charges({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Add Other Charges</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card fs-14">
                        <table class="table table-nowrap table-striped-columns mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">Charges Type</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Receiving Amount</th>
                                    <th scope="col">Remaining Amount</th>
                                    <th scope="col">Comments</th>
                                    <th scope="col">Added On</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach ($booking->otherCharges as $oc_key => $otherCharge)
                                <tr class="{{ $otherCharge->payment_status === 1 ? "table-success" : "table-danger" }}" id="otherCharges_{{ $otherCharge->id }}">
                                    <td><a href="javascript:void(0)" class="fw-semibold">{{ $oc_key+1 }}</a></td>
                                    <td>{{ $otherCharge->charges_type }}</td>
                                    <td><span class="text-primary">{{ $booking->currency . ' ' . number_format($otherCharge->amount, 2) }}</span></td>
                                    <td><span class="text-success">{{ $booking->currency . ' ' . number_format($otherCharge->reciving_amount, 2) }}</span></td>
                                    <td><span class="text-danger">{{ $booking->currency . ' ' . number_format($otherCharge->remaining_amount, 2) }}</span></td>
                                    <td>{{ $otherCharge->comments }}</td>
                                    <td>{{ $otherCharge->charges_at }}</td>
                                    <td>
                                        @if ($otherCharge->payment_status === 0 && Auth::user()->role <= 2)  {{-- && $otherCharge->charges_type == 'Date Change' --}}
                                        <div class="dropdown">
                                            <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-equalizer-fill"></i> </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item edit-list text-success" href="javascript:void(0)" onclick="add_oc_payment({{ $booking->id.','.$otherCharge->id }})"><i class="ri-secure-payment-line align-bottom me-2 "></i> Add Payment</a></li>
                                                <li><a class="dropdown-item edit-list text-danger" href="javascript:void(0)" onclick="delete_other_charges({{$otherCharge->id}})"><i class="ri-delete-bin-2-fill align-bottom me-2 "></i> Delete Charges</a>
                                                </li>

                                                {{-- <li><a class="dropdown-item edit-list text-warning" href="javascript:void(0)" onclick="edit_other_charges({{ $booking->id.','.$otherCharge->id }})"><i class="ri-pencil-fill align-bottom me-2 "></i> Edit Charges</a>
                                                </li>

                                                <li><a class="dropdown-item edit-list text-danger" href="javascript:void(0)" onclick="delete_other_charges({{$otherCharge->id}})"><i class="ri-delete-bin-2-fill align-bottom me-2 "></i> Delete Charges</a>
                                                </li> --}}

                                                {{-- <li class="dropdown-divider"></li>
                                                <li><a class="dropdown-item remove-list" href="#" data-id=' + x + ' data-bs-toggle="modal" data-bs-target="#removeItemModal"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li> --}}
                                            </ul>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div>

    </div>

    <div class="row">

        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-secondary">
                    <h4 class="card-title mb-0 flex-grow-1">Payments Details | <span class="text-warning">{{$payment_status->name}}</span></h4>
                    @if ($booking->payment_status !== 2)
                        @if (Auth::user()->role <= 2)
                            <button type="button" onclick="add_payment({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Add Payment</button>
                        @endif
                    @endif
                </div>

                <div class="card-body bg-body">

                    <div class="table-responsive table-card fs-14">
                        <table class="table table-nowrap table-striped-columns mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Installment Number</th>
                                    <th scope="col">Receiving Amount</th>
                                    <th scope="col">Remaining Amount</th>
                                    <th scope="col">Payment Method</th>
                                    <th scope="col">Payment Method Details</th>
                                    <th scope="col">Receipt Voucher</th>
                                    <th scope="col">Deposit Date</th>
                                    <th scope="col">Payment Added On</th>
                                    <th scope="col">Payment Comments</th>
                                    <th scope="col">Payment Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @php
                                    // Retrieve the latest payment (assumes payments are ordered by created_at descending)
                                    $latestPayment = $booking->payments->sortByDesc('payment_on')->first();
                                @endphp
                                @foreach ($booking->payments as $pay_key => $payment)
                                <tr id="payment_{{ $payment->id }}">
                                    <td><a href="javascript:void(0)" class="fw-semibold">
                                        {{-- {{ $payment->installment_no == 0 && empty($payment->other_charges_id) ? 'Down Payment' : $payment->installment_no }} --}}

                                        @if ($payment->installment_no == 0 && empty($payment->other_charges_id))
                                        {{ 'Down Payment' }}
                                        @elseif (!empty($payment->other_charges_id))
                                        {{ 'Other Charges' }}
                                        @else
                                        {{$payment->installment_no}}
                                        @endif
                                    </a></td>
                                    <td><span class="text-success">{{ $booking->currency . ' ' . number_format($payment->reciving_amount, 2) }}</span></td>
                                    <td><span class="text-danger">{{ $booking->currency . ' ' . number_format($payment->remaining_amount, 2) }}</span></td>
                                    <td>
                                        @if ($payment->payment_method == 'Credit Debit Card')
                                            @if ($payment->card_type_type != null)
                                                {{ $payment->card_type_type }}
                                            @else
                                                {{ $payment->payment_method }}
                                            @endif
                                        @else
                                            {{ $payment->payment_method }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($payment->payment_method == 'Credit Debit Card')
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs flex-shrink-0">
                                                <span class="avatar-title bg-light rounded-circle material-shadow">
                                                     <i class="bx bx-credit-card-alt h1 text-warning"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="fs-14 mb-1">{{ $payment->card_holder_name }}</h6>
                                                <p class="text-muted fs-12 mb-0">
                                                    <i class="mdi mdi-circle-medium text-success fs-15 align-middle"></i> {{ $payment->card_number }}
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0 text-end">
                                                <h6 class="mb-1 text-warning"><span class="text-uppercase ms-1">{{ $payment->card_type }}</span></h6>
                                                <p class="text-muted fs-13 mb-0">{{ $payment->card_expiry_date }} | {{ 'xxx' /* $payment->card_cvc */ }}</p>
                                            </div>
                                        </div>
                                        @endif
                                        @if ($payment->payment_method == 'Bank Transfer')
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs flex-shrink-0">
                                                    <span class="avatar-title bg-light rounded-circle material-shadow">
                                                        <i class="ri-bank-line h1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">{{ $payment->bank_name }}</h6>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($payment->receipt_voucher == null)
                                            <p class="text-danger"> No Voucher Uploaded</p>
                                        @else
                                        <a href="{{ asset('images/uploads/ReceiptVouchers').'/'.$payment->receipt_voucher }}" target="__blank"><img src="{{ asset('images/uploads/ReceiptVouchers') }}/{{ $payment->receipt_voucher }}" alt="" class="rounded avatar-xs material-shadow"></a>
                                        @endif
                                    </td>
                                    <td>{{ $payment->deposit_date }}</td>
                                    <td>{{ $payment->payment_on }}</td>
                                    <td>{{ $payment->comments }}</td>
                                    <td>
                                        @if ($payment->is_approved == 1)
                                            <p class="text-success"> Approved</p>
                                        @else
                                            <p class="text-danger"> Approval Pending</p>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- @if ($payment->id === $latestPayment->id) --}} {{-- && empty($payment->other_charges_id) --}}

                                        <div class="dropdown">
                                            <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-equalizer-fill"></i> </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                {{-- @if (Auth::user()->role <= 2) --}}
                                                    {{-- <li><a class="dropdown-item edit-list text-warning" href="javascript:void(0)" onclick="edit_payment({{ $booking->id.','.$payment->id }})"><i class="ri-pencil-fill align-bottom me-2 "></i> Edit</a>
                                                    </li> --}}
                                                     @if ($payment->is_approved === 0 && Auth::user()->role <= 2)
                                                        <li><button type="button" class="dropdown-item text-success approveReject_{{ $payment->id }}" onclick="approve_payment({{ $booking->id }}, {{$payment->id}})"><i class="ri-thumb-up-line align-bottom me-2"></i> Approve</button></li>
                                                    @endif
                                                    {{-- <li class="dropdown-di vider"></li> --}}
                                                    {{-- Delete button: only for latest payment --}}
                                                    @if ($payment->id === $latestPayment->id)
                                                        <li><a class="dropdown-item edit-list text-danger" href="javascript:void(0)" onclick="delete_payment({{$payment->id}})"><i class="ri-delete-bin-2-fill align-bottom me-2 "></i> Delete</a>
                                                        </li>
                                                    @endif
                                                {{-- @endif --}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div>

        <div class="col-lg-6">
            {{-- <pre>
            {{ print_r($booking->refunds) }}
            </pre> --}}
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex bg-danger">
                    <h4 class="card-title mb-0 flex-grow-1">Refund Details</h4>

                    @php
                        $refund_class = ($booking->deposite_amount > ($totalRefundedAmount + $totalServiceCharges)) ? "" : "disabled";
                    @endphp
                    <button type="button" onclick="add_refund({{ $booking->id }})" class="btn btn-soft-danger btn-sm d-none" {{ $refund_class }}> Add Refund</button>
                </div>

                <div class="card-body bg-body">

                    <div class="table-responsive table-card fs-14">
                        <table class="table table-nowrap table-striped-columns mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Sr. Number</th>
                                    <th scope="col">Refund Type</th>
                                    <th scope="col">Received Amount</th>
                                    <th scope="col">Refunded Amount</th>
                                    <th scope="col">Service Charges</th>
                                    <th scope="col">Remaining Amount</th>
                                    <th scope="col">Refund Method</th>
                                    <th scope="col">Refund Method Details</th>
                                    <th scope="col">Refund Requested On</th>
                                    <th scope="col">Refund Approve / Rejected On</th>
                                    <th scope="col">Instant Refund</th>
                                    <th scope="col">Refund Comments</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach ($booking->refunds as $ref_key => $refund)
                                <tr class="{{ $refund->refund_status === 1 ? "table-success" : (($refund->refund_status === 2) ? "table-danger" : "table-info") }}" id="refund_{{ $refund->id }}">
                                    <td>
                                        <a href="javascript:void(0)" class="fw-semibold"> {{ $ref_key +1 }} </a>
                                    </td>
                                    <td> {{$refund->refund_type." Refund"}} </td>
                                    <td><span class="text-success">{{ $booking->currency . ' ' . number_format($refund->paid_amount, 2) }}</span></td>
                                    <td><span class="text-danger">{{ $booking->currency . ' ' . number_format($refund->refunded_amount, 2) }}</span></td>
                                    <td><span class="text-warning">{{ $booking->currency . ' ' . number_format($refund->service_charges, 2) }}</span></td>
                                    <td><span class="text-danger">{{ $booking->currency . ' ' . number_format($refund->remaining_amount, 2) }}</span></td>
                                    <td>{{ $refund->payment_method }}</td>
                                    <td>
                                        @if ($refund->payment_method == 'Credit Debit Card')
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs flex-shrink-0">
                                                <span class="avatar-title bg-light rounded-circle material-shadow">
                                                     <i class="bx bx-credit-card-alt h1 text-warning"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="fs-14 mb-1">{{ $refund->card_holder_name }}</h6>
                                                <p class="text-muted fs-12 mb-0">
                                                    <i class="mdi mdi-circle-medium text-success fs-15 align-middle"></i> {{ $refund->card_number }}
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0 text-end">
                                                <h6 class="mb-1 text-warning"><span class="text-uppercase ms-1">{{ $refund->card_type }}</span></h6>
                                                <p class="text-muted fs-13 mb-0">{{ $refund->card_expiry_date }} | {{ $refund->card_cvc}}</p>
                                            </div>
                                        </div>
                                        @endif
                                        @if ($refund->payment_method == 'Bank Transfer')
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs flex-shrink-0">
                                                    <span class="avatar-title bg-light rounded-circle material-shadow">
                                                        <i class="ri-bank-line h1 text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">{{ $refund->bank_name }}</h6>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $refund->refund_requeseted_on }} / {{ $refund->user->name }}</td>
                                    <td> @if ($refund->refunded_on !=null)
                                        {{ $refund->refunded_on }} / {{ ($refund->updated_by !=null) ? userDetails($refund->updated_by)->name : "" }}
                                        @else
                                        Pending
                                    @endif</td>
                                    {{-- {{ userDetails($refund->updated_by )->name }} --}}
                                    <td>{{ ($refund->is_instant_refund == 1 ? "Yes" : "No") }}</td>
                                    <td>{{ $refund->comments }}</td>
                                    <td>
                                        @if ($refund->refund_status === 0 && Auth::user()->role <= 3)
                                            <div class="dropdown">
                                                <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-equalizer-fill"></i> </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                {{--  <li><a class="dropdown-item edit-list text-warning" href="javascript:void(0)" onclick="edit_refund({{ $booking->id.','.$refund->id }})"><i class="ri-pencil-fill align-bottom me-2 "></i> Edit</a>
                                                    </li> --}}
                                                    <li><a class="dropdown-item edit-list text-success" href="javascript:void(0)" onclick="approve_refund({{ $booking->id.','.$refund->id }})"><i class="ri-thumb-up-line align-bottom me-2 "></i> Approve</a>
                                                    </li>
                                                    <li><a class="dropdown-item edit-list text-danger" href="javascript:void(0)" onclick="reject_refund({{ $booking->id.','.$refund->id }})"><i class=" ri-thumb-down-line align-bottom me-2 "></i> Reject</a>
                                                    </li>
                                                    {{-- <li><a class="dropdown-item edit-list text-danger" href="javascript:void(0)" onclick="delete_refund({{$refund->id}})"><i class="ri-delete-bin-2-fill align-bottom me-2 "></i> Delete</a>
                                                    </li> --}}

                                                </ul>
                                            </div>
                                        @elseif ($refund->refund_status === 1)
                                        Approved
                                        @elseif ($refund->refund_status === 2)
                                        Rejected
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div>

    </div>

    {{-- PNR & Comments --}}
    <div class="row">
        @if ($booking->flight_pnr != null)
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex bg-body">
                        <h4 class="card-title mb-0 flex-grow-1">PNR Code</h4>
                        <button type="button" onclick="edit_pnr({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Update PNR</button>
                    </div><!-- end card header -->
                    <div class="card-body" id="flight_pnr">
                        {!! $booking->flight_pnr !!}
                    </div>
                </div>
            </div>
        @endif

        <div class="{{ ($booking->flight_pnr != null) ? 'col-lg-6' : 'col-lg-12' }}">
            <div class="card">
                <div class="card-header align-items-center d-flex bg-body">
                    <h4 class="card-title mb-0 flex-grow-1">Booking Comments <small class="text-danger"> These comments are visible on invoice for customer</small></h4>
                    <button type="button" onclick="edit_booking_comments({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Update Booking Comments</button>
                </div><!-- end card header -->
                <div class="card-body" id="comments">
                    <textarea name="comments" id="editor_1" class="form-control" rows="6" readonly>{!! $booking->comments !!}</textarea>
                </div>
            </div>
        </div>
    </div>
    {{-- Internal Comments --}}


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex bg-body">
                    <h4 class="card-title mb-0 flex-grow-1">Booking Internal Comments <small class="text-success"> These comments are inter company use only</small></h4>
                    <button type="button" onclick="add_booking_internal_comments({{ $booking->id }})" class="btn btn-soft-secondary btn-sm"> Add Booking Comments</button>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="profile-timeline">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            @foreach ($booking->internalComments->sortByDesc('id') as $icKey => $internalComment)


                            <div class="accordion-item border-0">
                                <div class="accordion-header" id="heading{{$icKey+1}}">
                                    <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse{{$icKey+1}}" aria-expanded="true" aria-controls="collapse{{$icKey+1}}">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 avatar-xs">
                                                <div class="avatar-title bg-primary rounded-circle">
                                                    <i class=" ri-message-3-line"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="fs-15 mb-0 fw-semibold">
                                                @if ($internalComment->title != null)
                                                    <span class="text-muted">{{ ucfirst($internalComment->title) }} | </span>
                                                @endif

                                                Comments By: {{userDetails($internalComment->created_by)->name}} - <span class="fw-normal">{{ $internalComment->created_at }}</span></h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div id="collapse{{$icKey+1}}" class="accordion-collapse collapse" aria-labelledby="heading{{$icKey+1}}" data-bs-parent="#accordionExample{{$icKey+1}}">
                                    <div class="accordion-body ms-2 ps-5 pt-0">

                                        <textarea class="form-control-plaintext" id="exampleFormControlTextarea{{$icKey+1}}" rows="10" readonly="">{!! $internalComment->comments !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            @endforeach

                        </div>
                        <!--end accordion-->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal-footer">
    <div class="hstack gap-2 justify-content-end">
        <input type="hidden" name="booking_id" id="booking_id" value="{{ $booking->id }}">
        <!-- Buttons with Label -->

        @if (Auth::user()->role <= 2)
        <button type="button" class="btn btn-sm btn-outline-success btn-label waves-effect waves-light" title="View Booking Logs" onclick="view_booking_log({{ $booking->id }})"><i class="ri-file-list-3-line fs-5 label-icon"></i> View Logs</button>
        @endif
        <button type="button" id="send-invoice-email-btn" onclick="send_invoice_via_email({{ $booking->id }})" class="btn btn-sm btn-outline-primary btn-label waves-effect waves-light"  title="Send Invoice Email"><i  class="ri-mail-send-line label-icon fs-6"></i> Send Invoice Email</button>


        <button type="button" onclick="view_send_email({{ $booking->id }})" class="btn btn-sm btn-outline-warning btn-label waves-effect waves-light"  title="View Email Template"><i  class="ri-mail-check-fill label-icon fs-6"></i> View Email Template</button>

        {{-- <button id="sendInvoiceButton" onclick="send_invoice_via_email({{ $booking->id }})" data-id="{{ $booking->id }}" class="btn btn-primary">Send Invoice</button> --}}
        <a href="{{ route('crm.view-booking-invoice', $booking->id) }}" class="btn btn-sm btn-outline-info btn-label waves-effect waves-light" title="View Booking Invoice" target="__blank"><i class="ri-printer-cloud-fill label-icon fs-6"></i> View Booking Invoice</a>
        <a href="{{ route('crm.generate-booking-invoice', $booking->id) }}" title="Download PDF" class="btn btn-sm btn-outline-dark btn-label waves-effect waves-light" target="__blank"><i class=" ri-file-pdf-line label-icon fs-6"></i> Download Invoice PDF</a>
        <a href="{{ route('crm.view-booking-eticket', $booking->id) }}" class="btn btn-sm btn-outline-secondary btn-label waves-effect waves-light" title="View Booking eTicket" target="__blank"><i class="ri-ticket-2-fill label-icon fs-6"></i> View Booking eTicket</a>
        {{-- <button type="button" class="btn btn-sm btn-outline-warning btn-label waves-effect waves-light"><i  class="ri-edit-circle-fill label-icon fs-6"></i></button>
        <button type="button" class="btn btn-sm btn-outline-danger btn-label waves-effect waves-light"><i class=" ri-delete-bin-2-fill label-icon fs-6"></i></button> --}}

        {{-- <div class="flex-shrink-0">
            <div class="dropdown card-header-dropdown">
                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="btn btn-sm btn-outline-success btn-label waves-effect waves-light">Invoice<i class="mdi mdi-chevron-down ms-1"></i></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" style="">
                    <a href="javascript:void(0)" onclick="send_invoice_via_email({{ $booking->id }})" class="btn btn-sm btn-outline-primary btn-label waves-effect waves-light"  title="Send Invoice Email">Send Invoice Email</a>
                    <a class="dropdown-item" href="#">Export</a>
                    <a class="dropdown-item" href="#">Import</a>
                </div>
            </div>
        </div> --}}
    </div>
    <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    {{-- <button type="button" class="btn btn-primary ">Save changes</button> --}}
</div>

<script>

    /* $(document).ready(function() {
        console.log('ok');
        $('#send-invoice-email-btn').click(function() {
            var bookingId = $(this).data('booking-id');

            $.ajax({
                url: '/send-invoice-email/' + bookingId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                },
                error: function(xhr, status, error) {
                    alert('Error sending invoice email: ' + xhr.responseJSON.message);
                }
            });
        });
    }); */

    function send_invoice_via_email(booking_id){
        /* Swal.fire(
            'Error',
            'This functionality still in development mode, please try again..',
            'error'
        ); */
        Swal.fire({
            title: 'Are you sure?',
            text: "To send invoice email to customer with pdf attached",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Send Email!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to approve the refund
                 $.ajax({
                    url: "{{ route('crm.send-invoice-email', ['booking_id' => ':booking_id']) }}"
                        .replace(':booking_id', booking_id),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        //console.log(response);
                        Swal.fire(
                            response.title,
                            response.message,
                            response.icon
                        );
                    },
                    error: function (response) {
                        console.log(response);
                        //alert('Error sending invoice email: ' + xhr.responseJSON.message);
                        Swal.fire({
                            title: response.responseJSON.title,
                            icon: response.responseJSON.icon,
                            text: response.responseJSON.message,
                        });
                        /* Swal.fire(
                            'Error!',
                            'There was a problem sending email.',
                            'error'
                        ); */

                    }
                });
            }
        });
    };

    function approve_refund(booking_id, refund_id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Once approve, you won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve Refund!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to approve the refund
                 $.ajax({
                    url: "{{ route('crm.approve-refund', ['booking_id' => ':booking_id', 'refund_id' => ':refund_id']) }}"
                        .replace(':booking_id', booking_id)
                        .replace(':refund_id', refund_id),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        //console.log(response);
                        if (response.success) {
                            Swal.fire(
                                'Approved!',
                                response.message,
                                'success'
                            );
                            //$('.modal.extraLargeModal').modal('toggle');
                            $('.modal.fullscreeexampleModal').modal('toggle');
                            view_booking(booking_id);
                            // Optionally, you can remove the deleted payment record from the DOM
                            //$('#payment_' + paymentId).remove();
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was a problem approving the refund.',
                                'error'
                            );
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        Swal.fire(
                            'Error!',
                            'There was a problem approving the refund.',
                            'error'
                        );
                    }
                });
            }
        });
    };

    function reject_refund(booking_id, refund_id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Once rejected, you won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Reject Refund!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to approve the refund
                 $.ajax({
                    url: "{{ route('crm.reject-refund', ['booking_id' => ':booking_id', 'refund_id' => ':refund_id']) }}"
                        .replace(':booking_id', booking_id)
                        .replace(':refund_id', refund_id),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        //console.log(response);
                        if (response.success) {
                            Swal.fire(
                                'Approved!',
                                response.message,
                                'success'
                            );
                            //$('.modal.extraLargeModal').modal('toggle');
                            $('.modal.fullscreeexampleModal').modal('toggle');
                            view_booking(booking_id);
                            // Optionally, you can remove the deleted payment record from the DOM
                            //$('#payment_' + paymentId).remove();
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was a problem rejecting the refund.',
                                'error'
                            );
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        Swal.fire(
                            'Error!',
                            'There was a problem rejecting the refund.',
                            'error'
                        );
                    }
                });
            }
        });
    };

    function approve_payment(booking_id, payment_id) {
        console.log(booking_id, payment_id);
        // var swalText = (payment_status == 1) ? "Approve" : "Reject";

        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure to approve this payment!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to delete the payment
                $.ajax({
                    url: '{{ route("crm.approve_payment", ["booking_id" => ":booking_id", "payment_id" => ":payment_id"]) }}'
                        .replace(':booking_id', booking_id)
                        .replace(':payment_id', payment_id)
                        // .replace(':payment_status', payment_status),
                    ,
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

                        $('.modal.fullscreeexampleModal').modal('toggle');
                        view_booking(booking_id);

                    },
                    error: function(xhr, status, error) {
                        //console.log(xhr, status, error);
                        Swal.fire({
                            title: xhr.responseJSON.title,
                            icon: xhr.responseJSON.icon,
                            text: xhr.responseJSON.message,
                        });
                    }
                });
            }
        });

    }

    function delete_payment(payment_id) {

        var paymentId = payment_id;
        var booking_id = $('#booking_id').val();
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
                    //url: "{{ route('crm.soft-delete-payment', "+ paymentId +") }}",
                    url: '{{ route("crm.soft-delete-payment", "") }}/' + paymentId,
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
                        $('.modal.fullscreeexampleModal').modal('toggle');
                        view_booking(booking_id);

                    },
                    error: function(xhr, status, error) {

                        Swal.fire({
                            title: xhr.responseJSON.title,
                            icon: xhr.responseJSON.icon,
                            text: xhr.responseJSON.message,
                        });
                    }
                });
            }
        });
    };

    function delete_other_charges(otherCharges_id) {

        var otherChargesId = otherCharges_id;
        var booking_id = $('#booking_id').val();
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
                    url: '{{ route("crm.soft-delete-other-charges", "") }}/' + otherChargesId,
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
                        $('.modal.fullscreeexampleModal').modal('toggle');
                        view_booking(booking_id);

                    },
                    error: function(xhr, status, error) {

                        Swal.fire({
                            title: xhr.responseJSON.title,
                            icon: xhr.responseJSON.icon,
                            text: xhr.responseJSON.message,
                        });
                    }
                });
            }
        });
    };

    function delete_pnr(pnr_id) {

        var pnrId = pnr_id;
        var booking_id = $('#booking_id').val();
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure to delete this pnr as you won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform AJAX request to delete the payment
                $.ajax({
                    //url: "{{ route('crm.soft-delete-payment', "+ paymentId +") }}",
                    url: '{{ route("crm.delete-pnr", "") }}/' + pnrId,
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
                        $('.modal.fullscreeexampleModal').modal('toggle');
                        view_booking(booking_id);

                    },
                    error: function(xhr, status, error) {
                        //console.log(xhr, status, error);
                        Swal.fire({
                            title: xhr.responseJSON.title,
                            icon: xhr.responseJSON.icon,
                            text: xhr.responseJSON.message,
                        });
                    }
                });
            }
    });
};
</script>
