<!-- resources/views/exports/bookings.blade.php -->

<table>
    <thead>
        <tr>
            <th><strong>Sr #</strong></th>
            <th><strong>Booking Date</strong></th>
            <th><strong>Booking #</strong></th>
            <th><strong>Passenger Name</strong></th>
            <th><strong>E-mail</strong></th>
            <th><strong>Agent</strong></th>
            <th><strong>Travel Date</strong></th>
            <th><strong>Departure ➝ Arrival</strong></th>
            <th><strong>Airline</strong></th>
            <th><strong>Status</strong></th>
            <th><strong>Sale</strong></th>
            <th><strong>Deposit</strong></th>
            <th><strong>Remaining</strong></th>
            <th><strong>Notes</strong></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bookings as $key => $booking)
        @php
            $passenger = $booking->passengers->first();
            $flight = $booking->flights->first();
        @endphp
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>
                {{ date('d-m-Y', strtotime($booking->created_at)) }}
            </td>
            <td>{{ $booking->booking_prefix.$booking->booking_number }}</td>
            <td>{{ $passenger->title . " " . $passenger->first_name . " " . $passenger->middle_name . " " . $passenger->last_name }}</td>
            <td>{{ $passenger->email }}</td>
            <td>{{ userDetails($booking->created_by)->name }}</td>
            <td>{{ $flight->departure_date }}</td>
            <td>{{ $flight->departure_airport}} ➝ {{$flight->arrival_airport}}</td>
            <td>{{ $flight->air_line_name }}</td>
            <td>{{ $booking->stausDetails(1, 'ticket_status')->first()->details; }}</td>
            <td>{{ $booking->currency }} {{number_format($booking->total_sales_cost, 2)}}</td>
            <td>{{ $booking->currency }} {{number_format($booking->deposite_amount,2)}}</td>
            <td>{{ $booking->currency }} {{$booking->balance_amount}}</td>
            <td>{{ $booking->internalComments->first()?->comments ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
