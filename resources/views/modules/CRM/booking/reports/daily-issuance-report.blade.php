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

<form action="{{ route('crm.daily-issuance-report') }}" id="dailyIssuanceReport" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex p-2">
                    <h4 class="card-title mb-0 flex-grow-1">Booking Daily Issuance Search</h4>
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
                        <div class="col-lg-3 d-none">
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
                        <div class="col-lg-3 mb-3 d-none">
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
                            <label>From Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="from_date"
                                    value="{{ $searchParams['from_date'] ?? date('Y-m-d') }}" placeholder="DD-MM-YYYY"
                                    class="form-control flatpickr-date" data-provider="flatpickr"
                                    id="from_date" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label>To Date</label>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                <input type="text" name="to_date"
                                    value="{{ $searchParams['to_date'] ?? date('Y-m-d') }}"
                                    placeholder="DD-MM-YYYY" class="form-control flatpickr-date"
                                    data-provider="flatpickr" id="to_date" autocomplete="off">
                            </div>
                        </div>

                    </div>

                </div>

                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="{{ route('crm.daily-issuance-report') }}" class="btn btn-danger"> <i class="ri-restart-line me-1 align-bottom"></i> Reset </a>
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
                    <h4 class="card-title mb-0 flex-grow-1">Daily Issuance Report</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    {{-- <pre>
                        {{ print_r($records) }}
                    </pre> --}}
                    <div class="table-responsive">
                        {{-- <table id="alternative-pagination" class="alternative-pagination table nowrap dt-responsive align-middle table-hover table-bordered align-middle mb-0 fs-12" data-ordering="false"> --}}
                            <table id="alternative-pagination" class="alternative-pagination table nowrap align-middle table-hover table-bordered align-middle mb-0 fs-12" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Sr #</th>
                                    <th scope="col">Booking Date</th>
                                    <th scope="col">Invoice Number</th>
                                    <th scope="col">Client/Passenger Name</th>
                                    <th scope="col">Agent Name</th>
                                    <th scope="col">Service Type</th>
                                    <th scope="col">Service Details</th>
                                    <th scope="col" class="d-none">Route or Location (Sector or City)</th>
                                    <th scope="col">Travel/Stay Date</th>
                                    <th scope="col">Class/Room/Category</th>
                                    <th scope="col">Reference Number</th>
                                    <th scope="col">GDS Ref No</th>
                                    <th scope="col">Supplier Ref No</th>
                                    <th scope="col">Vendor Name</th>
                                    <th scope="col">Issue Date</th>
                                    <th scope="col">Issued By</th>
                                    <th scope="col">Invoice Status</th>
                                    <th scope="col">Net Amount</th>
                                    <th scope="col">Actual Amount</th>
                                    <th scope="col">Aviation Fee</th>
                                    <th scope="col">Profit</th>
                                    <th scope="col">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($records as $record)
                                    {{-- <pre> {{ print_r($record) }} </pre> --}}
                                    @php
                                        $passenger = $record->booking->passengers->first();
                                        $passengerName = $passenger->title." ".$passenger->first_name." ".$passenger->middle_name." ".$passenger->last_name;

                                        $actual_net_cost = $record->actual_net_cost + ($record->aviation_fee ?? 0);

                                        $flightNetTotal = 0;
                                        if ($record->booking->prices) {
                                            $flightNetTotal = $record->booking->prices->where('pricing_type', 'bookingFlight')->sum('net_total');
                                        }
                                        // $flightNetTotal = $record->booking->prices ->where('pricing_type', 'bookingFlight') ->sum('net_total');

                                        // $displayedNetTotal = $flightNetTotal != null && $flightNetTotal != 0 ? $flightNetTotal : record->net_cost;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $record->booking->booking_date }}</td>
                                        <td><a href="javascript:void(0)">{{ $record->booking->booking_prefix.' '.$record->booking->booking_number }}</a></td>
                                        <td>{{ $passengerName }}</td>
                                        <td>{{ userDetails($record->created_by)->name }}</td>
                                        <td>{{ $record->record_type }}</td>
                                        <!--Service Details-->
                                        <td>
                                            @switch($record->record_type)
                                                @case('Transport')
                                                    <!-- Display Transport Specific Data -->
                                                    <span>{{ $record->car_type }}</span>
                                                    @break

                                                @case('Hotel')
                                                    <!-- Display Hotel Specific Data -->
                                                    {{ $record->hotel_name }}
                                                    @break

                                                @case('Flight')

                                                    {{-- <span>{{ optional(optional($record->booking)->flights->first())->air_line_name ?? 'N/A' }}</span> --}}
                                                    <span>{{ $record->flights->first()->air_line_name ?? 'N/A' }}</span>
                                                    @break

                                                @case('Visa')
                                                    <!-- Display Visa Specific Data -->
                                                    <span>{{ $record->visa_category }}</span>
                                                    @break

                                                @default
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>Unknown Type</span>
                                            @endswitch
                                        </td>
                                        <!--Route or location-->
                                        <td class="d-none">
                                            @switch($record->record_type)
                                                @case('Transport')
                                                    <!-- Display Transport Specific Data -->
                                                    <span>{{ $record->location }}</span>
                                                    @break

                                                @case('Hotel')
                                                    <!-- Display Hotel Specific Data -->
                                                    {{ $record->hotel_name }}
                                                    @break

                                                @case('Flight')

                                                    {{-- <span>{{ optional(optional($record->booking)->flights->first())->air_line_name ?? 'N/A' }}</span> --}}
                                                    @foreach ($record->flights as $flight)
                                                        <span>
                                                            {{ $flight->departure_airport.' -> '.$flight->arrival_airport }}
                                                        </span>
                                                        @if (!$loop->last)
                                                            <br>
                                                        @endif

                                                    @endforeach

                                                    @break

                                                @case('Visa')
                                                    <!-- Display Visa Specific Data -->
                                                    <span>{{ $record->visa_country }}</span>
                                                    @break

                                                @default
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>Unknown Type</span>
                                            @endswitch
                                        </td>
                                        <!--Travel / Stay date-->
                                        <td>
                                            @switch($record->record_type)
                                                @case('Transport')
                                                    <!-- Display Transport Specific Data -->
                                                    <span>{{ $record->transport_date }}</span>
                                                    @break

                                                @case('Hotel')
                                                    <!-- Display Hotel Specific Data -->
                                                    {{ $record->check_in_date }}
                                                    @break

                                                @case('Flight')

                                                    <span>{{ optional($record->flights->first())->departure_date ?? 'N/A' }}</span>

                                                    @break

                                                @case('Visa')
                                                    <!-- Display Visa Specific Data -->
                                                    <span>-</span>
                                                    @break

                                                @default
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>Unknown Type</span>
                                            @endswitch
                                        </td>
                                        <!--Class/Room/Category-->
                                        <td>
                                            @switch($record->record_type)
                                                @case('Transport')
                                                    <!-- Display Transport Specific Data -->
                                                    <span>{{ $record->car_type }}</span>
                                                    @break

                                                @case('Hotel')
                                                    <!-- Display Hotel Specific Data -->
                                                    {{ $record->room_type }}
                                                    @break

                                                @case('Flight')

                                                    <span>{{ optional($record->flights->first())->booking_class ?? 'N/A' }}</span>

                                                    @break

                                                @case('Visa')
                                                    <!-- Display Visa Specific Data -->
                                                    <span>{{ $record->remarks }}</span>
                                                    @break

                                                @default
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>Unknown Type</span>
                                            @endswitch
                                        </td>
                                        <!-- Reference Number (Ticket/Confirmation/Visa No) -->
                                        <td>
                                            @switch($record->record_type)
                                                @case('Transport')
                                                    <!-- Display Transport Specific Data -->
                                                    <span>{{ '-' }}</span>
                                                    @break

                                                @case('Hotel')
                                                    <!-- Display Hotel Specific Data -->
                                                    {{ $record->hotel_confirmation_number }}
                                                    @break

                                                @case('Flight')

                                                    <span>{{ optional($record->flights->first())->ticket_no ?? 'N/A' }}</span>

                                                    @break

                                                @case('Visa')
                                                    <!-- Display Visa Specific Data -->
                                                    <span>{{ '-' }}</span>
                                                    @break

                                                @default
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>Unknown Type</span>
                                            @endswitch
                                        </td>
                                        <!--  GDS Ref No (or Booking Ref) -->
                                        <td>
                                            @switch($record->record_type)

                                                @case('Flight')

                                                    <span>{{ optional($record->flights->first())->gds_no ?? 'N/A' }}</span>

                                                    @break

                                                @default
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>N/A</span>
                                            @endswitch
                                        </td>
                                        <!-- Supplier Ref No/-->
                                        <td>-</td>
                                        <!-- Vendor Name -->
                                        <td> {{ $record->supplier }} </td>
                                        <td>{{ date('d-m-Y', strtotime($record->actual_net_on)) }}</td>
                                        <td>{{ ($record->actual_net_by != null) ? userDetails($record->actual_net_by)->name : '-' }}</td>
                                        <td> {{ $record->booking->stausDetails(1, 'ticket_status')->first()->details }} </td>

                                        <td>
                                            @switch($record->record_type)

                                                @case('Flight')

                                                    <span>{{ $flightNetTotal }}</span>

                                                    @break

                                                @default
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>{{ number_format($record->net_cost, 2) }}</span>
                                            @endswitch
                                        </td>
                                        {{-- <td>{{ number_format($record->net_cost, 2) }}</td> --}}
                                        {{-- <td>{{ number_format($displayedNetTotal, 2) }}</td> --}}
                                        <td>{{ number_format($record->actual_net_cost, 2) }}</td>
                                        {{-- <td>{{ number_format($actual_net_cost, 2) }}</td> --}}
                                        <td>{{ number_format($record->aviation_fee ?? 0, 2) }}</td>
                                        <td>
                                            @switch($record->record_type)

                                                @case('Flight')

                                                    <span>{{ number_format(($flightNetTotal - $record->actual_net_cost), 2) }}</span>

                                                    @break

                                                @default
                                                    <!-- Default case if type doesn't match any -->
                                                    <span>{{ number_format(($record->net_cost - $record->actual_net_cost), 2) }}</span>
                                            @endswitch
                                        </td>
                                        {{-- <td>{{ number_format(($record->net_cost - $record->actual_net_cost), 2) }}</td> --}}
                                        <td>{{ $record->comments }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="21" class="text-center">No records found</td>
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
        var startDatePicker = flatpickr("#from_date", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            defaultDate: today,
            onChange: function(selectedDates, dateStr, instance) {
                // Set the selected date of the start date input as the min date for the end date input
                endDatePicker.set('minDate', selectedDates[0]);
            }
        });

        // Initialize Flatpickr for the end date input
        var endDatePicker = flatpickr("#to_date", {
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
            var formData = $('#dailyIssuanceReport').serialize(); // Assuming your form has an ID "bookingSearchForm"

            // Send AJAX request to export filtered data to Excel
            $.ajax({
                url: "{{ route('crm.export-daily-issuance-report-to-excel') }}",
                method: 'POST',
                data: formData, // Pass the serialized form data
                success: function(response) {
                    console.log(response);
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
