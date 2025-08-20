<!-- resources/views/exports/bookings.blade.php -->

<table>
    <thead>
        <tr>
            <th><strong>Sr #</strong></th>
            <th><strong>Booking Date</strong></th>
            <th><strong>Invoice #</strong></th>
            <th><strong>Agent</strong></th>
            <th><strong>Total Installments</strong></th>
            <th><strong>Installments Received</strong></th>
            <th><strong>Installments Remaining</strong></th>
            <th><strong>Next Installment Amount</strong></th>
            <th><strong>Next Due Date</strong></th>
            <th><strong>Notes</strong></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bookings as $key => $payment)
        @php
            $passenger = $payment->booking->passengers->first();
        @endphp
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>
                {{ date('d-m-Y', strtotime($payment->booking->created_at)) }}
            </td>
            <td>{{ $payment->booking->booking_prefix.$payment->booking->booking_number }}</td>
            <td>{{ userDetails($payment->booking->created_by)->name }}</td>
            <td>{{ $payment->booking->total_installment }}</td>
            <td>{{ $payment->installment_number }}</td>
            <td>{{ $payment->booking->total_installment-$payment->installment_number }}</td>
            <td>{{ $payment->amount }}</td>
            <td>{{ $payment->due_date }}</td>
            <td>-</td>
        </tr>
        @endforeach
    </tbody>
</table>
