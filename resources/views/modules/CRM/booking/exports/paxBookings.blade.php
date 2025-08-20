<!-- resources/views/exports/bookings.blade.php -->

<table>
    <thead>
        <tr>
            <th><strong>INVOICE #</strong></th>
            <th><strong>PACKAGE</strong></th>
            <th><strong>BOOKING DATE</strong></th>
            <th><strong>CLIENT NAME</strong></th>
            <th><strong>A/C Head</strong></th>
            <th><strong>DEPARTURE DATE</strong></th>
            <th><strong>ARRIVAL DATE</strong></th>
            <th><strong>DEPARTURE AIRPORT</strong></th>
            <th><strong>ARRIVAL AIRPORT</strong></th>
            <th><strong>AGENT NAME</strong></th>
            <th><strong>No. Of PAX</strong></th>
            <th><strong>Airline</strong></th>
            <th><strong>GDS PNR</strong></th>
            <th><strong>BOOKING STATUS</strong></th>
            <th><strong>TOTAL SALE COST</strong></th>
            <th><strong>Actual Net</strong></th>
            <th><strong>Agent Net</strong></th>
            <th><strong>DEPOSIT</strong></th>
            <th><strong>REMAINING AMOUNT</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $booking)
        @php
            $flight = $booking->flights->first();
            $passenger = $booking->passengers->first();
            $totalPassengers = $booking->passengers->count();
            $installmentPlan = $booking->installmentPlan
                ->where('is_received', 0)
                ->sortByDesc('due_date')
                ->first();
            $totalOtherCharges = $booking->otherCharges->isNotEmpty() ? $booking->otherCharges->sum('amount') : "0.00";
            $total_with_other_charges = $booking->total_sales_cost + $totalOtherCharges;
            $profit_in_invoice = ($total_with_other_charges-$booking->total_net_cost)-$booking->refunded_amount;
        @endphp
        <tr>
            <td>{{ $booking->booking_prefix.$booking->booking_number }}</td>
            <td>-</td>
            <td>{{ $booking->booking_date }}</td>
            <td>{{ $passenger->title . " " . $passenger->first_name . " " . $passenger->middle_name . " " . $passenger->last_name }}</td>
            <td>-</td>
            @if (empty($flight))
                <td colspan="4">-</td>
            @else
            <td>{{ $flight->departure_date }}</td>
            <td>{{ $flight->arrival_date }}</td>
            <td>{{ $flight->departure_airport }}</td>
            <td>{{ $flight->arrival_airport }}</td>
            @endif
            <td>{{ userDetails($booking->created_by)->name }}</td>
            <td>{{ $totalPassengers }}</td>
            <td>{{ empty($flight) ? "" : $flight->air_line_name }}</td>
            <td>{{ empty($flight) ? "" : $flight->gds_no }}</td>
            <td>{{ $booking->stausDetails(1, 'ticket_status')->first()->details; }}</td>
            <td>{{ $booking->currency.' '.number_format($booking->total_sales_cost, 2)}}</td>
            <td>0.00</td>
            <td>{{ $booking->currency.' '.number_format($booking->total_net_cost, 2)}}</td>
            <td>{{ $booking->currency.' '.number_format($booking->deposite_amount,2)}}</td>
            <td>{{ $booking->currency.' '.$booking->balance_amount}}</td>

        </tr>
        @endforeach
    </tbody>
</table>
