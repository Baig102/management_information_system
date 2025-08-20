<!-- resources/views/exports/bookings.blade.php -->

<table>
    <thead>
        <tr>
            <th><strong>Sr #</strong></th>
            <th><strong>Booking Date</strong></th>
            <th><strong>Invoice Number</strong></th>
            <th><strong>Client/Passenger Name</strong></th>
            <th><strong>Agent Name</strong></th>
            <th><strong>Service Type</strong></th>
            <th><strong>Service Details</strong></th>
            {{-- <th><strong>Route or Location</strong></th> --}}
            <th><strong>Travel/Stay Date</strong></th>
            <th><strong>Class/Room/Category</strong></th>
            <th><strong>Reference Number</strong></th>
            <th><strong>GDS Ref No</strong></th>
            <th><strong>Supplier Ref No</strong></th>
            <th><strong>Vendor Name</strong></th>
            <th><strong>Issue Date</strong></th>
            <th><strong>Issued By</strong></th>
            <th><strong>Invoice Status</strong></th>
            <th><strong>Net Amount</strong></th>
            <th><strong>Actual Amount</strong></th>
            <th><strong>Aviation Fee</strong></th>
            <th><strong>Profit</strong></th>
            <th><strong>Remarks</strong></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bookings as $record)

            @php
                $passenger = $record->booking->passengers->first();
                $passengerName = $passenger->title." ".$passenger->first_name." ".$passenger->middle_name." ".$passenger->last_name;

                $actual_net_cost = $record->actual_net_cost + ($record->aviation_fee ?? 0);

                $flightNetTotal = 0;
                if ($record->booking->prices) {
                    $flightNetTotal = $record->booking->prices->where('pricing_type', 'bookingFlight')->sum('net_total');
                }
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
                {{-- <td>
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
                </td> --}}
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
                <td>{{ number_format($record->actual_net_cost, 2) }}</td>
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
                <td>{{ $record->comments }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="21" class="text-center">No records found</td>
            </tr>
        @endforelse
    </tbody>
</table>
