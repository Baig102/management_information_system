<!-- resources/views/exports/bookings.blade.php -->

<table>
    <thead>
        <tr>
            <th><strong>INVOICE #</strong></th>
            <th><strong>PAYMENT TERM</strong></th>
            <th><strong>TOTAL</strong></th>
            <th><strong>RECEIVING AMOUNT</strong></th>
            <th><strong>REMAINING AMOUNT</strong></th>
            <th><strong>INSTALLMENT NUMBER</strong></th>
            <th><strong>PAYMENT METHOD</strong></th>
            <th><strong>PAYMENT METHOD DETAILS</strong></th>
            <th><strong>PAYMENT ON</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->booking->booking_prefix.$payment->booking->booking_number }}</td>
            <td>{{ $payment->booking->booking_payment_term == 2 ? "Full Payment" : "Installment"  }}</td>
            <td>{{ $payment->booking->currency.' '.number_format($payment->booking->total_sales_cost, 2)}}</td>
            <td>{{ $payment->booking->currency.' '.number_format($payment->reciving_amount, 2)}}</td>
            <td>{{ $payment->booking->currency.' '.number_format($payment->remaining_amount, 2)}}</td>
            <td>{{ $payment->installment_no }}</td>
            <td>{{ $payment->payment_method }}</td>
            <td>
                {{ $payment->payment_method }}
                @if ($payment->payment_method == 'Credit Debit Card')
                <p>{{ $payment->card_holder_name }}</p>
                <p>{{ $payment->card_number }}</p>
                <p>{{ $payment->card_type }}</p>
                <p>{{ $payment->card_expiry_date }} | {{ 'xxx'}}</p>
                @endif
                @if ($payment->payment_method == 'Bank Transfer')
                    <p>{{ $payment->bank_name }}</p>
                @endif
            </td>
            <td>{{ $payment->payment_on }}</td>

        </tr>
        @endforeach
    </tbody>
</table>
