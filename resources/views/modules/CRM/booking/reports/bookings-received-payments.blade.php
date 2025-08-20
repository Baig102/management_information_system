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
            Booking
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
<script>
    // Define the base URL for your view booking route
    const viewBookingUrl = '{{ route('crm.view-booking', ['id' => 'booking_id']) }}';
</script>

<form action="{{ route('crm.bookings-received-payments') }}" id="bookingPaymentsSearchForm" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Booking Payments Search</h4>
                    @if (Auth::user()->role <=3)
                        <!-- Your Export to Excel button -->
                        <div class="flex-shrink-0">
                            <button type="button" id="exportToExcelBtn" class="btn btn-soft-info btn-sm material-shadow-none">
                                <i class="ri-file-list-3-line align-middle"></i> Export to Excel
                            </button>
                        </div>
                    @endif
                </div><!-- end card header -->
                <div class="card-body bg-light">

                    @csrf
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="company" class="form-label">Select Company</label>
                            <select class="select2 form-control-sm" id="company" name="company_id"
                                data-placeholder="Select Company">
                                <option></option>
                                @foreach ($assignedCompanies as $assiCompany)
                                    <option value="{{ $assiCompany->id }}"
                                        {{ isset($searchParams['company_id']) && $searchParams['company_id'] == $assiCompany->id ? 'selected' : '' }}>
                                        {{ $assiCompany->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label>Booking Number</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="booking_number"
                                    value="{{ $searchParams['booking_number'] ?? '' }}"
                                    placeholder="Enter Booking Number" class="form-control" id="booking_number"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>Booking From Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="payment_from_date"
                                    value="{{ $searchParams['payment_from_date'] ?? date('Y-m-d') }}" placeholder="DD-MM-YYYY"
                                    class="form-control flatpickr-date" data-provider="flatpickr"
                                    id="payment_from_date" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>Booking To Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="payment_to_date"
                                    value="{{ $searchParams['payment_to_date'] ?? date('Y-m-d') }}"
                                    placeholder="DD-MM-YYYY" class="form-control flatpickr-date"
                                    data-provider="flatpickr" id="payment_to_date" autocomplete="off">
                            </div>
                        </div>

                        {{-- <div class="col-lg-2">
                            <label for="orderBy" class="form-label">Select Order By</label>
                            <select class="select2 form-control-sm" id="orderBy" name="created_by" data-placeholder="Select Order By">
                                <option value="payment_on"> Payment On </option>
                                <option value="booking_number"> Booking Number </option>
                            </select>
                        </div> --}}

                        {{-- <div class="col-lg-2">
                            <label for="agent" class="form-label">Select Agent</label>
                            <select class="select2 form-control-sm" id="agent" name="created_by" data-placeholder="Select Agent">
                                <option></option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ isset($searchParams['created_by']) && $searchParams['created_by'] == $agent->id ? 'selected' : ((auth()->user()->role > 2 && auth()->user()->id == $agent->id) ? 'selected' : ''); }}>
                                        {{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>

                </div>

                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="{{ route('crm.bookings-received-payments') }}" class="btn btn-danger"> <i class="ri-restart-line me-1 align-bottom"></i> Reset </a>
                        <button type="submit" class="btn btn-primary"> <i class="ri-equalizer-fill me-1 align-bottom"></i> Filter </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Booking Received Payments</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    {{-- <pre>
                        {{ print_r($payments) }}
                    </pre> --}}
                    <div class="table-responsive">
                        <table id="alternative-pagination" class="alternative-pagination table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12" data-ordering="false">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Booking Details</th>
                                    <th scope="col">Payment Details</th>
                                    <th scope="col">Payment Method</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($payments as $key => $payment)

                                    <tr>
                                        <td>
                                            <a href="javascript:void(0)" onclick="view_booking({{ $payment->booking->id }})" class="fw-semibold me-2"><i class="ri-coupon-line align-middle text-muted fs-12 me-2"></i>Booking #: {{ $payment->booking->booking_prefix . " " . $payment->booking->booking_number }}
                                            </a>
                                            <p class="mb-0"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Company:</span> {{ $payment->booking->company->name }}</p>
                                            <p class="mb-0"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Bookind Date:</span> {{ date("M-d-Y", strtotime($payment->booking->booking_date)) }}</p>
                                            <p class="mb-0 text-secondary"><i class="ri-calendar-check-fill align-middle text-muted fs-12 me-2"></i><span class="fw-semibold">Payment Status:</span>
                                            @if ($payment->booking->payment_status == 1)
                                                <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Pending </span>
                                            @else
                                                <span class="text-success"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Paid </span>
                                            @endif</p>
                                        </td>
                                        <td>
                                            <div class="card-header p-0 border-0 bg-soft-light">
                                                <div class="row g-0 text-center">

                                                    <div class="col-6 col-sm-2">
                                                        <div class="p-1 border border-dashed">
                                                            <h5 class="mb-1">
                                                                @if ($payment->installment_no == 0 && empty($payment->other_charges_id))
                                                                    {{ 'Down Payment' }}
                                                                @elseif (!empty($payment->other_charges_id))
                                                                    {{ 'Other Charges' }}
                                                                @else
                                                                    {{$payment->installment_no}}
                                                                @endif
                                                            </h5>
                                                            <p class="text-muted mb-0">Installment Number</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->

                                                    <div class="col-6 col-sm-2">
                                                        <div class="p-1 border border-dashed">
                                                            <h5 class="mb-1 text-info"><span class="counter-value" data-target="{{$payment->total_amount}}"> {{ $payment->booking->currency }} {{number_format($payment->total_amount,2)}}</span></h5>
                                                            <p class="text-muted mb-0">Total Amount</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-6 col-sm-2">
                                                        <div class="p-1 border border-dashed border-start-0">
                                                            <h5 class="mb-1 text-success"><span class="counter-value" data-target="{{$payment->reciving_amount}}">{{ $payment->booking->currency }} {{number_format($payment->reciving_amount,2)}}</span></h5>
                                                            <p class="text-muted mb-0">Deposit Amount</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-6 col-sm-2">
                                                        <div class="p-1 border border-dashed border-start-0">
                                                            <h5 class="mb-1">{{ date('M-d-Y', strtotime($payment->payment_on))}}</h5>
                                                            <p class="text-muted mb-0">Deposit Date</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-6 col-sm-2">
                                                        <div class="p-1 border border-dashed border-start-0">
                                                            <h5 class="mb-1 text-danger"><span class="counter-value" data-target="{{$payment->remaining_amount}}">{{ $payment->booking->currency }} {{number_format($payment->remaining_amount,2 )}}</span></h5>
                                                            <p class="text-muted mb-0">Balance Amount</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->

                                                </div>
                                            </div><!-- end card header -->
                                        </td>
                                        <td>
                                            <h5>{{ $payment->payment_method }}</h5>
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center"> No Record Found!</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                        <!-- end table -->
                        {{-- {{ $bookings->links() }} --}}
                        <!-- Pagination Links -->
                        {{-- {{ $payments->links('pagination::bootstrap-4') }} --}}
                    </div>
                    <!-- end table responsive -->
                </div>
            </div>
        </div>
    </div>

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

        // Get today's date
        var today = new Date();
        // Set oneMonthPrior to the first day of the current month
        var oneMonthPrior = new Date(today.getFullYear(), today.getMonth(), 1);

        // Initialize Flatpickr for the start date input
        var startDatePicker = flatpickr("#payment_from_date", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            //defaultDate: oneMonthPrior,
            onChange: function(selectedDates, dateStr, instance) {
                // Set the selected date of the start date input as the min date for the end date input
                endDatePicker.set('minDate', selectedDates[0]);
            }
        });

        // Initialize Flatpickr for the end date input
        var endDatePicker = flatpickr("#payment_to_date", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                // Set the selected date of the end date input as the max date for the start date input
                startDatePicker.set('maxDate', selectedDates[0]);
            }
        });

    });

    $(document).ready(function() {
        // Handle the export button click event
        $('#exportToExcelBtn').on('click', function() {
            // Serialize the entire form to capture all filter fields
            var formData = $('#bookingPaymentsSearchForm').serialize(); // Assuming your form has an ID "bookingSearchForm"

            // Send AJAX request to export filtered data to Excel
            $.ajax({
                url: "{{ route('crm.export-bookings-received-payments-to-excel') }}",
                method: 'POST',
                data: formData, // Pass the serialized form data
                success: function(response) {
                    //console.log(response);
                    // If the request was successful, download the file
                    if (response.url) {
                        window.location.href = response.url;
                    } else {
                        alert('Failed to generate the Excel file.');
                    }
                },
                error: function(xhr) {
                    //console.error(xhr.responseText);
                    alert('An error occurred while exporting the data.');
                }
            });
        });
    });
</script>
@endsection
