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
            CRM
        @endslot
    @endcomponent
    <script>
        // Define the base URL for your view booking route
        const viewBookingUrl = '{{ route('crm.view-booking', ['id' => 'booking_id']) }}';
    </script>
    {{-- <pre>
        {{ print_r($approvalPendingPayments) }}
    </pre> --}}
    {{-- @if (count($approvalPendingPayments) > 0) --}}
    {{-- Pending Payments Approved Booking List --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1"><span class="text-danger">Booking</span> Payments Approval List</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="alternative-pagination" class="alternative-pagination table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Invoice #</th>
                                    <th scope="col">Installment #</th>
                                    <th scope="col">Receiving Amount</th>
                                    <th scope="col">Payment Method</th>
                                    <th scope="col">Deposit Date</th>
                                    <th scope="col">Receipt Voucher</th>
                                    <th scope="col">Created By</th>
                                    <th scope="col">Created On</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($approvalPendingPayments as $record)

                                    <tr id="payment_{{$record->id}}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td><a href="javascript:void(0)" onclick="view_booking({{ $record->booking->id }})">{{ $record->booking->booking_prefix.' '.$record->booking->booking_number }}</a></td>
                                        <td>{{ ($record->installment_no == 0 || $record->installment_no == null ) ? 'Down Payment' : $record->installment_no }}</td>
                                        <td>{{ number_format($record->reciving_amount, 2) }}</td>
                                        <td>{{ $record->payment_method }} </td>
                                        <td>{{ date('d-m-Y', strtotime($record->deposit_date)) }}</td>
                                        <td>
                                            @if ($record->receipt_voucher == null)
                                                <p class="text-danger"> No Voucher Uploaded</p>
                                            @else
                                                <a href="{{ asset('images/uploads/ReceiptVouchers').'/'.$record->receipt_voucher }}" target="__blank"><img src="{{ asset('images/uploads/ReceiptVouchers') }}/{{ $record->receipt_voucher }}" alt="" class="rounded avatar-xs material-shadow"></a>
                                            @endif
                                        </td>
                                        <td>{{ userDetails($record->created_by)->name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($record->created_at)) }}</td>
                                        <td>
                                            @if (Auth::user()->role <= 2 && $record->is_approved == 0)
                                                <button type="button" onclick="approve_payment({{$record->booking_id.','.$record->id}})" class="btn btn-success btn-label btn-sm"><i class="ri-thumb-up-line label-icon align-middle fs-16 me-2"></i> Approve</button>
                                            @else
                                                @if ($record->is_approved == 1)
                                                    <p class="text-success"> Approved</p>
                                                @else
                                                    <p class="text-danger"> Approval Pending</p>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No records found</td>
                                    </tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>
    {{-- @endif --}}
    {{-- Seven Days Close Traveling Booking List --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1"><span class="text-danger">Seven Days</span> Close Traveling Booking List</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        {{-- <table class="table align-middle mb-0 fs-12"> --}}
                        <table id="alternative-pagination" class="alternative-pagination table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12" data-ordering="false" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Booking Details</th>
                                    <th scope="col">Passenger Details</th>
                                    <th scope="col">Flight Details</th>
                                    <th scope="col">Payment Details</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sortedBookings as $key => $booking)
                                    @php
                                        $flight = $booking->flights->first();
                                        $passenger = $booking->passengers->first();

                                        $firstDepartureDate = Carbon\Carbon::parse(
                                            $booking->flights->first()->departure_date ?? null,
                                        );
                                        $rowClass = '';
                                        if ($firstDepartureDate->isToday()) {
                                            $rowClass = 'table-danger';
                                        } elseif ($firstDepartureDate->isTomorrow()) {
                                            $rowClass = 'table-warning';
                                        } else {
                                            $rowClass = 'table-success';
                                        }
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td>
                                            <a href="javascript:void(0)" onclick="view_booking({{ $booking->id }})"
                                                class="fw-semibold me-2"><i
                                                    class="ri-coupon-line align-middle fs-12 me-2"></i>Booking #:
                                                {{ $booking->booking_prefix . ' ' . $booking->booking_number }}
                                            </a>
                                            <p class="mb-0"><i
                                                    class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span
                                                    class="fw-semibold">Company:</span> {{ $booking->company->name }}</p>
                                            <p class="mb-0 text-success"><i
                                                    class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span
                                                    class="fw-semibold">Bookind Date:</span>
                                                {{ date('M-d-Y', strtotime($booking->booking_date)) }}</p>
                                            <p class="mb-0 text-secondary"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Booking Status:</span> {{ $booking->stausDetails(1, 'ticket_status')->first()->details; }}</p>
                                            <hr>
                                            <p class="mb-0"><i class="ri-user-follow-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Created By:</span> {{userDetails($booking->created_by)->name}} </p>
                                            <p class="mb-0"><i class="ri-calendar-2-line align-middle fs-12 me-2"></i><span class="fw-semibold">Created On:</span> {{ date('M-d-Y', strtotime($booking->created_at)) }}</p>
                                            @if ($booking->updated_by != null)
                                                <p class="mb-0 text-danger"><i class="ri-shield-user-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Updated By:</span> {{userDetails($booking->updated_by)->name}} </p>
                                                <p class="mb-0 text-danger"><i class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Updated On:</span> {{ date('M-d-Y', strtotime($booking->updated_at)) }}</p>
                                            @endif

                                        </td>
                                        <td>
                                            <span>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="fs-12 mb-1">
                                                            <a href="javascript:void(0)" class="link-primary">
                                                                {{ $passenger->title . ' ' . $passenger->first_name . ' ' . $passenger->middle_name . ' ' . $passenger->last_name }}
                                                            </a>
                                                        </h5>
                                                        <p class=" mb-0"><a
                                                                href="tel:{{ $passenger->mobile_number }}" target="__blank"
                                                                class=""><span class="fw-medium"><i
                                                                        class="ri-phone-line me-2 align-middle fs-16"></i>{{ $passenger->mobile_number }}</span></a>
                                                        </p>
                                                        <p class=" mb-0"><a href="mailto:{{ $passenger->email }}"
                                                                class="" target="__blank"><span
                                                                    class="fw-medium"><i
                                                                        class="ri-mail-line me-2 align-middle fs-16"></i>{{ $passenger->email }}</span></a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </span>
                                        </td>
                                        <td>
                                            @if (empty($flight))
                                                <span class="ustify-content-center text-center text-danger">No Record
                                                    Found</span>
                                            @else
                                                {{-- <div class="card border card-border-primary"> --}}
                                                <div
                                                    class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                                    <div class="card-body">
                                                        <div class="ribbon ribbon-info ribbon-shape">
                                                            {{ $booking->trip_type == 1 ? 'One Way' : 'Return' }}
                                                            <i class="ri-arrow-right-line align-middle"></i>
                                                            {{ $booking->flight_type == 1 ? 'Direct' : 'In-Direct' }}
                                                            <i class="ri-arrow-left-right-line align-middle"></i>
                                                            Ticket:
                                                            {{ $booking->ticket_status == 1 ? 'Pending' : 'Generated' }}

                                                        </div>
                                                        <div class="row mt-4">
                                                            <div class="col-lg-5">
                                                                <h6 class="mb-2 text-primary">Departure Details</h6>
                                                                <p class="mb-2 fw-medium text-truncate"><i
                                                                        class="ri-flight-takeoff-line"></i>
                                                                    {{ $flight->departure_airport }}</p>
                                                                <p class="mb-0"><i class=" ri-send-plane-fill"></i>
                                                                    {{ $flight->air_line_name }}</p>
                                                                <p class="mb-0"><i class="ri-calendar-todo-line"></i>
                                                                    {{ date("M-d-Y", strtotime($flight->departure_date)) }} | <i
                                                                        class="ri-calendar-check-line"></i>
                                                                    {{ $flight->departure_time }}</p>
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <spain class="text-danger display-6"><i
                                                                        class="ri-plane-line"></i></spain>
                                                            </div>
                                                            <div class="col-lg-5">
                                                                <h6 class="mb-2 text-warning">Arrival Details</h6>
                                                                <p class="mb-2 fw-medium text-truncate"><i
                                                                        class="ri-flight-land-line"></i>
                                                                    {{ $flight->arrival_airport }}</p>
                                                                <p class="mb-0"><i class="ri-send-plane-fill"></i>
                                                                    {{ $flight->air_line_name }}</p>
                                                                <p class="mb-0"><i class="ri-calendar-todo-line"></i>
                                                                    {{ date("M-d-Y", strtotime($flight->arrival_date)) }} | <i
                                                                        class="ri-calendar-check-line"></i>
                                                                    {{ $flight->arrival_time }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-primary">
                                                    <div class="row">
                                                        <div class="col-lg-6 text-white">GRD PNR: {{ $flight->gds_no }}</div>
                                                        <div class="col-lg-6 text-white">Ticket Deadline: {{ ($booking->ticket_deadline != null) ? date("M-d-Y", strtotime($booking->ticket_deadline)) : 'Null' }}</div>
                                                    </div>
                                                    {{--<div class="text-center">
                                                        <a href="javascript:void(0);" class="link-light">Connect Now <i class="ri-arrow-right-s-line align-middle lh-1"></i></a>
                                                    </div>--}}
                                                </div>
                                                </div>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                                <div class="card-body">
                                                    <ul class="list-group list-group-flush border-dashed mb-0 mt-0 pt-0">
                                                        <li class="list-group-item px-0">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 avatar-xs">
                                                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                                                        <img src="{{ URL::asset('build/images/svg/crypto-icons/gbp.svg') }}"
                                                                            class="img-fluid" alt="">
                                                                    </span>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <h6 class="mb-1">Amount Details</h6>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Sales Cost </p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Net Cost </p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Margin </p>
                                                                </div>
                                                                <div class="flex-shrink-0 text-end">
                                                                    <h6 class="mb-1">-</h6>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->total_sales_cost, 2)}}</p>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->total_net_cost, 2)}}</p>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->margin, 2)}}</p>
                                                                </div>
                                                            </div>
                                                        </li><!-- end -->
                                                        <li class="list-group-item px-0">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 avatar-xs">
                                                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                                                        <img src="{{ URL::asset('build/images/svg/crypto-icons/bix.svg') }}"
                                                                            class="img-fluid" alt="">
                                                                    </span>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <h6 class="mb-1">Deposit Details</h6>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Deposit Amount</p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Last Deposit Date</p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Balance Amount</p>
                                                                </div>
                                                                <div class="flex-shrink-0 text-end">
                                                                    <h6 class="mb-1">
                                                                        @if ($booking->payment_status == 1)
                                                                        <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Pending </span>
                                                                    @elseif ($booking->payment_status == 2)
                                                                        <span class="text-success"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Paid </span>
                                                                    @elseif ($booking->payment_status == 3)
                                                                        <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Balance Pending </span>
                                                                    @elseif ($booking->payment_status == 4)
                                                                        <span class="text-warning"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Partially Refunded </span>
                                                                    @elseif ($booking->payment_status == 5)
                                                                        <span class="text-warning"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Refunded </span>
                                                                    @endif
                                                                    </h6>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->deposite_amount,2)}}</p>
                                                                    <p class="fs-12 mb-0">{{ date('M-d-Y', strtotime($booking->deposit_date))}}</p>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{$booking->balance_amount}}</p>
                                                                </div>
                                                            </div>
                                                        </li><!-- end -->
                                                    </ul><!-- end -->
                                                    {{-- <div class="ribbon ribbon-primary ribbon-shape">{{ $booking->payment_type == 1 ? "Full Payment" : "Installment"; }} <i class="ri-arrow-right-line align-middle"></i> Payment: {{ $booking->payment_status == 1 ? "Pending" : "Fully Paid" }}</div> --}}
                                                    <div class="row d-none">
                                                        <div class="col-lg-6 border-end border-2">
                                                            <h6 class="mb-2 text-primary">Amount Details</h6>
                                                            <p class="mb-0">Sales Cost: {{ $booking->currency }} {{number_format($booking->total_sales_cost, 2)}}</p>
                                                            <p class="mb-0">Net Cost: {{ $booking->currency }} {{number_format($booking->total_net_cost, 2)}}</p>
                                                            <p class="mb-0">Margin: {{ $booking->currency }} {{number_format($booking->margin, 2)}}</p>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <h6 class="mb-2 text-warning">Deposit Details</h6>
                                                            <p class="mb-0">Deposit Amount: {{ $booking->currency }} {{number_format($booking->deposite_amount,2)}}</p>
                                                            <p class="mb-0">Last Deposit Date: {{ date('M-d-Y', strtotime($booking->deposit_date))}}</p>
                                                            <p class="mb-0">Balance Amount {{ $booking->currency }} {{$booking->balance_amount}}</p>
                                                            <p class="mb-0">Payment:
                                                                @if ($booking->payment_status == 1)
                                                                    <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Pending </span>
                                                                @else
                                                                    <span class="text-success"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Paid </span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"> <i
                                                        class="ri-equalizer-fill"></i> </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li class=""><button type="button" class="dropdown-item text-primary" onclick="view_booking({{ $booking->id }})"><i class="ri-eye-fill align-bottom me-2"></i> View</button>
                                                    </li>

                                                    </li>
                                                    <li><a class="dropdown-item edit-list text-dark"
                                                            href="{{ route('crm.view-booking-invoice', $booking->id) }}"
                                                            target="__blank"><i
                                                                class=" ri-printer-line align-bottom me-2 "></i> Print</a>
                                                    <li><a class="dropdown-item edit-list text-success" href="#"><i
                                                                class=" ri-mail-send-line align-bottom me-2 "></i>
                                                            Email</a>
                                                    <li><a class="dropdown-item edit-list text-secondary"
                                                            href="{{ route('crm.generate-booking-invoice', $booking->id) }}"
                                                            target="__blank"><i
                                                                class="ri-file-pdf-line align-bottom me-2 "></i> Download
                                                            PDF</a>
                                                    </li>
                                                    {{-- <li class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item remove-list" href="#" data-id=' + x + ' data-bs-toggle="modal" data-bs-target="#removeItemModal"><i class="ri-delete-bin-fill align-bottom me-2"></i> Delete</a></li> --}}
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="17" class="text-center"> No Record Found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- end table -->
                        {{-- {{ $bookings->links() }} --}}
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>

    {{-- Seven Days Ticket Deadline Booking List --}}
     <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1"><span class="text-danger">Seven Days</span> Ticket Deadline Booking List</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="alternative-pagination" class="alternative-pagination table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12" data-ordering="false" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Booking Details</th>
                                    <th scope="col">Passenger Details</th>
                                    <th scope="col">Flight Details</th>
                                    <th scope="col">Payment Details</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ticketDeadlineBookings as $key => $booking)
                                    @php
                                        $flight = $booking->flights->first();
                                        $passenger = $booking->passengers->first();

                                        $deadlineDate = \Carbon\Carbon::parse($booking->ticket_deadline);
                                        $rowClass = $deadlineDate->isPast() ? 'table-danger' : ($deadlineDate->between($today, $nextDate) ? 'table-warning' : 'table-success');
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td>
                                            <a href="javascript:void(0)" onclick="view_booking({{ $booking->id }})" class="fw-semibold me-2"><i class="ri-coupon-line align-middle fs-12 me-2"></i>Booking #: {{ $booking->booking_prefix . " " . $booking->booking_number }}
                                            </a>
                                            <p class="mb-0"><i class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Company:</span> {{ $booking->company->name }}</p>
                                            <p class="mb-0 text-success"><i class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Bookind Date:</span> {{ date("M-d-Y", strtotime($booking->booking_date)) }}</p>
                                            <p class="mb-0 text-secondary"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Booking Status:</span> {{ $booking->stausDetails(1, 'ticket_status')->first()->details; }}</p>
                                            <hr>
                                            <p class="mb-0"><i class="ri-user-follow-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Created By:</span> {{userDetails($booking->created_by)->name}} </p>
                                            <p class="mb-0"><i class="ri-calendar-2-line align-middle fs-12 me-2"></i><span class="fw-semibold">Created On:</span> {{ date('M-d-Y', strtotime($booking->created_at)) }}</p>
                                            @if ($booking->updated_by != null)
                                                <p class="mb-0 text-danger"><i class="ri-shield-user-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Updated By:</span> {{userDetails($booking->updated_by)->name}} </p>
                                                <p class="mb-0 text-danger"><i class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Updated On:</span> {{ date('M-d-Y', strtotime($booking->updated_at)) }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <span>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="fs-12 mb-1">
                                                            <a href="javascript:void(0)" class="link-primary">
                                                                {{ $passenger->first_name . " " . $passenger->middle_name . " " . $passenger->last_name }}
                                                            </a>
                                                        </h5>
                                                        <p class=" mb-0"><a href="tel:{{ $passenger->mobile_number }}" target="__blank" class=""><span class="fw-medium"><i class="ri-phone-line me-2 align-middle fs-16"></i>{{ $passenger->mobile_number }}</span></a></p>
                                                        <p class=" mb-0"><a href="mailto:{{ $passenger->email }}" class="" target="__blank"><span class="fw-medium"><i class="ri-mail-line me-2 align-middle fs-16"></i>{{ $passenger->email }}</span></a></p>
                                                    </div>
                                                </div>
                                            </span>
                                        </td>
                                        <td>
                                            @if (empty($flight))
                                            <span class="ustify-content-center text-center text-danger">No Record Found</span>
                                            @else
                                            {{-- <div class="card border card-border-primary"> --}}
                                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                                <div class="card-body">
                                                    <div class="ribbon ribbon-info ribbon-shape">
                                                        {{ $booking->trip_type == 1 ? "One Way" : "Return"; }}
                                                        <i class="ri-arrow-right-line align-middle"></i>
                                                        {{ $booking->flight_type == 1 ? "Direct" : "In-Direct"; }}
                                                        <i class="ri-arrow-left-right-line align-middle"></i>
                                                        Ticket: {{ $booking->ticket_status == 1 ? "Pending" : "Generated"; }}

                                                    </div>
                                                    <div class="row mt-4">
                                                        <div class="col-lg-5">
                                                            <h6 class="mb-2 text-primary">Departure Details</h6>
                                                            <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-takeoff-line"></i> {{$flight->departure_airport}}</p>
                                                            <p class="mb-0"><i class=" ri-send-plane-fill"></i> {{$flight->air_line_name}}</p>
                                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i> {{date("M-d-Y", strtotime($flight->departure_date))}} | <i class="ri-calendar-check-line"></i> {{$flight->departure_time}}</p>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <spain class="text-danger display-6"><i class="ri-plane-line"></i></spain>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <h6 class="mb-2 text-warning">Arrival Details</h6>
                                                            <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-land-line"></i> {{$flight->arrival_airport}}</p>
                                                            <p class="mb-0"><i class="ri-send-plane-fill"></i> {{$flight->air_line_name}}</p>
                                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i> {{date("M-d-Y", strtotime($flight->arrival_date))}} | <i class="ri-calendar-check-line"></i> {{$flight->arrival_time}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-primary">
                                                    <div class="row">
                                                        <div class="col-lg-6 text-white">GRD PNR: {{ $flight->gds_no }}</div>
                                                        <div class="col-lg-6 text-white">Ticket Deadline: {{ ($booking->ticket_deadline != null) ? date("M-d-Y", strtotime($booking->ticket_deadline)) : 'Null' }}</div>
                                                    </div>
                                                    {{--<div class="text-center">
                                                        <a href="javascript:void(0);" class="link-light">Connect Now <i class="ri-arrow-right-s-line align-middle lh-1"></i></a>
                                                    </div>--}}
                                                </div>
                                            </div>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                                <div class="card-body">
                                                    <ul class="list-group list-group-flush border-dashed mb-0 mt-0 pt-0">
                                                        <li class="list-group-item px-0">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 avatar-xs">
                                                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                                                        <img src="{{ URL::asset('build/images/svg/crypto-icons/gbp.svg') }}"
                                                                            class="img-fluid" alt="">
                                                                    </span>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <h6 class="mb-1">Amount Details</h6>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Sales Cost </p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Net Cost </p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Margin </p>
                                                                </div>
                                                                <div class="flex-shrink-0 text-end">
                                                                    <h6 class="mb-1">-</h6>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->total_sales_cost, 2)}}</p>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->total_net_cost, 2)}}</p>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->margin, 2)}}</p>
                                                                </div>
                                                            </div>
                                                        </li><!-- end -->
                                                        <li class="list-group-item px-0">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 avatar-xs">
                                                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                                                        <img src="{{ URL::asset('build/images/svg/crypto-icons/bix.svg') }}"
                                                                            class="img-fluid" alt="">
                                                                    </span>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <h6 class="mb-1">Deposit Details</h6>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Deposit Amount</p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Last Deposit Date</p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Balance Amount</p>
                                                                </div>
                                                                <div class="flex-shrink-0 text-end">
                                                                    <h6 class="mb-1">
                                                                        @if ($booking->payment_status == 1)
                                                                        <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Pending </span>
                                                                    @elseif ($booking->payment_status == 2)
                                                                        <span class="text-success"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Paid </span>
                                                                    @elseif ($booking->payment_status == 3)
                                                                        <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Balance Pending </span>
                                                                    @elseif ($booking->payment_status == 4)
                                                                        <span class="text-warning"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Partially Refunded </span>
                                                                    @elseif ($booking->payment_status == 5)
                                                                        <span class="text-warning"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Refunded </span>
                                                                    @endif
                                                                    </h6>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->deposite_amount,2)}}</p>
                                                                    <p class="fs-12 mb-0">{{ date('M-d-Y', strtotime($booking->deposit_date))}}</p>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{$booking->balance_amount}}</p>
                                                                </div>
                                                            </div>
                                                        </li><!-- end -->
                                                    </ul><!-- end -->
                                                    {{-- <div class="ribbon ribbon-primary ribbon-shape">{{ $booking->payment_type == 1 ? "Full Payment" : "Installment"; }} <i class="ri-arrow-right-line align-middle"></i> Payment: {{ $booking->payment_status == 1 ? "Pending" : "Fully Paid" }}</div> --}}
                                                    <div class="row d-none">
                                                        <div class="col-lg-6 border-end border-2">
                                                            <h6 class="mb-2 text-primary">Amount Details</h6>
                                                            <p class="mb-0">Sales Cost: {{ $booking->currency }} {{number_format($booking->total_sales_cost, 2)}}</p>
                                                            <p class="mb-0">Net Cost: {{ $booking->currency }} {{number_format($booking->total_net_cost, 2)}}</p>
                                                            <p class="mb-0">Margin: {{ $booking->currency }} {{number_format($booking->margin, 2)}}</p>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <h6 class="mb-2 text-warning">Deposit Details</h6>
                                                            <p class="mb-0">Deposit Amount: {{ $booking->currency }} {{number_format($booking->deposite_amount,2)}}</p>
                                                            <p class="mb-0">Last Deposit Date: {{ date('M-d-Y', strtotime($booking->deposit_date))}}</p>
                                                            <p class="mb-0">Balance Amount {{ $booking->currency }} {{$booking->balance_amount}}</p>
                                                            <p class="mb-0">Payment:
                                                                @if ($booking->payment_status == 1)
                                                                    <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Pending </span>
                                                                @else
                                                                    <span class="text-success"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Paid </span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-equalizer-fill"></i> </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li class=""><button type="button" class="dropdown-item text-primary" onclick="view_booking({{ $booking->id }})"><i class="ri-eye-fill align-bottom me-2"></i> View</button>
                                                    </li>
                                                    </li>
                                                    <li><a class="dropdown-item edit-list text-dark" href="{{ route('crm.view-booking-invoice', $booking->id) }}" target="__blank"><i class=" ri-printer-line align-bottom me-2 "></i> Print</a>
                                                    <li><a class="dropdown-item edit-list text-success" href="#"><i class=" ri-mail-send-line align-bottom me-2 "></i> Email</a>
                                                    <li><a class="dropdown-item edit-list text-secondary" href="{{ route('crm.generate-booking-invoice', $booking->id) }}" target="__blank"><i class="ri-file-pdf-line align-bottom me-2 "></i> Download PDF</a>
                                                    </li>
                                                    {{-- <li class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item remove-list" href="#" data-id=' + x + ' data-bs-toggle="modal" data-bs-target="#removeItemModal"><i class="ri-delete-bin-fill align-bottom me-2"></i> Delete</a></li> --}}
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="17" class="text-center"> No Record Found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- end table -->
                        {{-- {{ $bookings->links() }} --}}
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>

    {{-- Seven Days Pending Payments Booking List --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1"><span class="text-danger">Seven Days</span> Booking Payments Deadline</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="alternative-pagination" class="alternative-pagination table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12" data-ordering="false" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Booking Details</th>
                                    <th scope="col">Passenger Details</th>
                                    <th scope="col">Flight Details</th>
                                    <th scope="col">Payment Details</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendingInstallmentBookings as $key => $booking)
                                    @php
                                        $flight = $booking->flights->first();
                                        $passenger = $booking->passengers->first();

                                        $deadlineDate = \Carbon\Carbon::parse($booking->ticket_deadline);
                                        $rowClass = $deadlineDate->isPast() ? 'table-danger' : ($deadlineDate->between($today, $nextDate) ? 'table-warning' : 'table-success');
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td>
                                            <a href="javascript:void(0)" onclick="view_booking({{ $booking->id }})" class="fw-semibold me-2"><i class="ri-coupon-line align-middle fs-12 me-2"></i>Booking #: {{ $booking->booking_prefix . " " . $booking->booking_number }}
                                            </a>
                                            <p class="mb-0"><i class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Company:</span> {{ $booking->company->name }}</p>
                                            <p class="mb-0 text-success"><i class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Bookind Date:</span> {{ date("M-d-Y", strtotime($booking->booking_date)) }}</p>
                                            <p class="mb-0 text-secondary"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Booking Status:</span> {{ $booking->stausDetails(1, 'ticket_status')->first()->details; }}</p>
                                            <hr>
                                            <p class="mb-0"><i class="ri-user-follow-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Created By:</span> {{userDetails($booking->created_by)->name}} </p>
                                            <p class="mb-0"><i class="ri-calendar-2-line align-middle fs-12 me-2"></i><span class="fw-semibold">Created On:</span> {{ date('M-d-Y', strtotime($booking->created_at)) }}</p>
                                            @if ($booking->updated_by != null)
                                                <p class="mb-0 text-danger"><i class="ri-shield-user-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Updated By:</span> {{userDetails($booking->updated_by)->name}} </p>
                                                <p class="mb-0 text-danger"><i class="ri-calendar-check-fill align-middle fs-12 me-2"></i><span class="fw-semibold">Updated On:</span> {{ date('M-d-Y', strtotime($booking->updated_at)) }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <span>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="fs-12 mb-1">
                                                            <a href="javascript:void(0)" class="link-primary">
                                                                {{ $passenger->title . " " . $passenger->first_name . " " . $passenger->middle_name . " " . $passenger->last_name }}
                                                            </a>
                                                        </h5>
                                                        <p class=" mb-0"><a href="tel:{{ $passenger->mobile_number }}" target="__blank" class=""><span class="fw-medium"><i class="ri-phone-line me-2 align-middle fs-16"></i>{{ $passenger->mobile_number }}</span></a></p>
                                                        <p class=" mb-0"><a href="mailto:{{ $passenger->email }}" class="" target="__blank"><span class="fw-medium"><i class="ri-mail-line me-2 align-middle fs-16"></i>{{ $passenger->email }}</span></a></p>
                                                    </div>
                                                </div>
                                            </span>
                                        </td>
                                        <td>
                                            @if (empty($flight))
                                            <span class="ustify-content-center text-center text-danger">No Record Found</span>
                                            @else
                                            {{-- <div class="card border card-border-primary"> --}}
                                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                                <div class="card-body">
                                                    <div class="ribbon ribbon-info ribbon-shape">
                                                        {{ $booking->trip_type == 1 ? "One Way" : "Return"; }}
                                                        <i class="ri-arrow-right-line align-middle"></i>
                                                        {{ $booking->flight_type == 1 ? "Direct" : "In-Direct"; }}
                                                        <i class="ri-arrow-left-right-line align-middle"></i>
                                                        Ticket: {{ $booking->ticket_status == 1 ? "Pending" : "Generated"; }}

                                                    </div>
                                                    <div class="row mt-4">
                                                        <div class="col-lg-5">
                                                            <h6 class="mb-2 text-primary">Departure Details</h6>
                                                            <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-takeoff-line"></i> {{$flight->departure_airport}}</p>
                                                            <p class="mb-0"><i class=" ri-send-plane-fill"></i> {{$flight->air_line_name}}</p>
                                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i> {{date("M-d-Y", strtotime($flight->departure_date))}} | <i class="ri-calendar-check-line"></i> {{$flight->departure_time}}</p>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <spain class="text-danger display-6"><i class="ri-plane-line"></i></spain>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <h6 class="mb-2 text-warning">Arrival Details</h6>
                                                            <p class="mb-2 fw-medium text-truncate"><i class="ri-flight-land-line"></i> {{$flight->arrival_airport}}</p>
                                                            <p class="mb-0"><i class="ri-send-plane-fill"></i> {{$flight->air_line_name}}</p>
                                                            <p class="mb-0"><i class="ri-calendar-todo-line"></i> {{date("M-d-Y", strtotime($flight->arrival_date))}} | <i class="ri-calendar-check-line"></i> {{$flight->arrival_time}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-primary">
                                                    <div class="row">
                                                        <div class="col-lg-6 text-white">GRD PNR: {{ $flight->gds_no }}</div>
                                                        <div class="col-lg-6 text-white">Ticket Deadline: {{ ($booking->ticket_deadline != null) ? date("M-d-Y", strtotime($booking->ticket_deadline)) : 'Null' }}</div>
                                                    </div>
                                                    {{--<div class="text-center">
                                                        <a href="javascript:void(0);" class="link-light">Connect Now <i class="ri-arrow-right-s-line align-middle lh-1"></i></a>
                                                    </div>--}}
                                                </div>
                                            </div>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="card ribbon-box border shadow-none mb-lg-0 left material-shadow">
                                                <div class="card-body">
                                                    <ul class="list-group list-group-flush border-dashed mb-0 mt-0 pt-0">
                                                        <li class="list-group-item px-0">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 avatar-xs">
                                                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                                                        <img src="{{ URL::asset('build/images/svg/crypto-icons/gbp.svg') }}"
                                                                            class="img-fluid" alt="">
                                                                    </span>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <h6 class="mb-1">Amount Details</h6>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Sales Cost </p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Net Cost </p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>Margin </p>
                                                                </div>
                                                                <div class="flex-shrink-0 text-end">
                                                                    <h6 class="mb-1">-</h6>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->total_sales_cost, 2)}}</p>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->total_net_cost, 2)}}</p>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->margin, 2)}}</p>
                                                                </div>
                                                            </div>
                                                        </li><!-- end -->
                                                        <li class="list-group-item px-0">
                                                            <div class="d-flex">
                                                                <div class="flex-shrink-0 avatar-xs">
                                                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                                                        <img src="{{ URL::asset('build/images/svg/crypto-icons/bix.svg') }}"
                                                                            class="img-fluid" alt="">
                                                                    </span>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <h6 class="mb-1">Deposit Details</h6>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Deposit Amount</p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Last Deposit Date</p>
                                                                    <p class="fs-12 mb-0"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>Balance Amount</p>
                                                                </div>
                                                                <div class="flex-shrink-0 text-end">
                                                                    <h6 class="mb-1">
                                                                        @if ($booking->payment_status == 1)
                                                                        <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Pending </span>
                                                                    @elseif ($booking->payment_status == 2)
                                                                        <span class="text-success"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Paid </span>
                                                                    @elseif ($booking->payment_status == 3)
                                                                        <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Balance Pending </span>
                                                                    @elseif ($booking->payment_status == 4)
                                                                        <span class="text-warning"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Partially Refunded </span>
                                                                    @elseif ($booking->payment_status == 5)
                                                                        <span class="text-warning"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Refunded </span>
                                                                    @endif
                                                                    </h6>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{number_format($booking->deposite_amount,2)}}</p>
                                                                    <p class="fs-12 mb-0">{{ date('M-d-Y', strtotime($booking->deposit_date))}}</p>
                                                                    <p class="fs-12 mb-0">{{ $booking->currency }} {{$booking->balance_amount}}</p>
                                                                </div>
                                                            </div>
                                                        </li><!-- end -->
                                                    </ul><!-- end -->
                                                    {{-- <div class="ribbon ribbon-primary ribbon-shape">{{ $booking->payment_type == 1 ? "Full Payment" : "Installment"; }} <i class="ri-arrow-right-line align-middle"></i> Payment: {{ $booking->payment_status == 1 ? "Pending" : "Fully Paid" }}</div> --}}
                                                    <div class="row d-none">
                                                        <div class="col-lg-6 border-end border-2">
                                                            <h6 class="mb-2 text-primary">Amount Details</h6>
                                                            <p class="mb-0">Sales Cost: {{ $booking->currency }} {{number_format($booking->total_sales_cost, 2)}}</p>
                                                            <p class="mb-0">Net Cost: {{ $booking->currency }} {{number_format($booking->total_net_cost, 2)}}</p>
                                                            <p class="mb-0">Margin: {{ $booking->currency }} {{number_format($booking->margin, 2)}}</p>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <h6 class="mb-2 text-warning">Deposit Details</h6>
                                                            <p class="mb-0">Deposit Amount: {{ $booking->currency }} {{number_format($booking->deposite_amount,2)}}</p>
                                                            <p class="mb-0">Last Deposit Date: {{ date('M-d-Y', strtotime($booking->deposit_date))}}</p>
                                                            <p class="mb-0">Balance Amount {{ $booking->currency }} {{$booking->balance_amount}}</p>
                                                            <p class="mb-0">Payment:
                                                                @if ($booking->payment_status == 1)
                                                                    <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Pending </span>
                                                                @else
                                                                    <span class="text-success"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Paid </span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"> <i class="ri-equalizer-fill"></i> </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li class=""><button type="button" class="dropdown-item text-primary" onclick="view_booking({{ $booking->id }})"><i class="ri-eye-fill align-bottom me-2"></i> View</button>
                                                    </li>

                                                    </li>
                                                    <li><a class="dropdown-item edit-list text-dark" href="{{ route('crm.view-booking-invoice', $booking->id) }}" target="__blank"><i class=" ri-printer-line align-bottom me-2 "></i> Print</a>
                                                    <li><a class="dropdown-item edit-list text-success" href="#"><i class=" ri-mail-send-line align-bottom me-2 "></i> Email</a>
                                                    <li><a class="dropdown-item edit-list text-secondary" href="{{ route('crm.generate-booking-invoice', $booking->id) }}" target="__blank"><i class="ri-file-pdf-line align-bottom me-2 "></i> Download PDF</a>
                                                    </li>
                                                    {{-- <li class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item remove-list" href="#" data-id=' + x + ' data-bs-toggle="modal" data-bs-target="#removeItemModal"><i class="ri-delete-bin-fill align-bottom me-2"></i> Delete</a></li> --}}
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="17" class="text-center"> No Record Found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- end table -->
                        {{-- {{ $bookings->links() }} --}}
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>



@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-crm.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
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

                            // $('.modal.fullscreeexampleModal').modal('toggle');
                            // view_booking(booking_id);

                            $('#payment_' + payment_id).fadeOut(300, function() {
                                $(this).remove();
                            });

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
    </script>
@endsection
