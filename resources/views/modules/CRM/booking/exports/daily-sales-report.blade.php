<!-- resources/views/exports/bookings.blade.php -->

<table>
    <thead>
        <tr>
            <th><strong>Sr #</strong></th>
            <th><strong>Booking Date</strong></th>
            <th><strong>Invoice #</strong></th>
            <th><strong>Transaction Type</strong></th>
            <th><strong>First Head</strong></th>
            <th><strong>Narration</strong></th>
            <th><strong>Against Head</strong></th>
            <th><strong>Amount</strong></th>
            <th><strong>Funds Category</strong></th>
            <th><strong>Agent</strong></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bookings as $key => $booking)

        @php
            $passenger = $booking->passengers->first();
        @endphp
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>
                {{ date('d-m-Y', strtotime($booking->created_at)) }}
            </td>
            <td>{{ $booking->booking_prefix.$booking->booking_number }}</td>
            <td>{{ 'Funds Received In' }}</td>
            <td>
                @if (!$booking->payments->isEmpty())
                    @if ($booking->payments[0]->payment_method == 'Bank Transfer')
                        {{ $booking->payments[0]->bank_name }}
                    @elseif ($booking->payments[0]->payment_method == 'Credit Debit Card')
                        {{ $booking->payments[0]->card_type_type }}
                    @endif
                @endif
            </td>

            <td>{{ 'From' }}</td>
            <td>{{ $booking->booking_prefix.$booking->booking_number.' '. $passenger->title . " " . $passenger->first_name . " " . $passenger->middle_name . " " . $passenger->last_name }}</td>
            <td>
                @if (!$booking->payments->isEmpty())
                    £ {{ $booking->payments[0]->reciving_amount }}
                @else
                    £ 0.00
                @endif
            </td>

            <td>
                @if (!$booking->payments->isEmpty())
                    {{ $booking->payments[0]->payment_method }}
                @endif
            </td>
            <td>{{ userDetails($booking->created_by)->name }}</td>

        </tr>
        @endforeach
    </tbody>
</table>
