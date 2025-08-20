<!DOCTYPE html>
<html>
<head>
    <title>Booking Invoice From {{ $booking->company->name }}</title>
    {{-- <style>
        table, th, td {
            border: 1px solid;
            width:100%;
            border-collapse:collapse
        }
    </style> --}}
</head>
<body>
    <div style="margin-bottom: 20px;">

        <div style="width: 100%;">

            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 10px;">Dear Valued Passenger,</h6>
            <p>Greetings from {{ $booking->company->name }}</p>
            <p style="margin:0px; padding:0px;">Please check your invoice, flight details and name spellings (Compulsory: surname and first name as per passport). If there are any mistakes please do let us know by email so that we can make it correct. In case if everything is correct and you agree with that then reply back with "OK GO AHEAD" so that we can proceed with your booking for further steps.</p>

            <p style="margin:0px; padding:0px;">Please note by replying "yes" or "All details are correct" or " I Agree ok " and any other confirmation to this email means you agree to our terms and conditions and the details or your booking including names, flight details and other package details are correct.</p>

        </div>

    </div>
    <hr>
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between;">

        <div style="width: 48%;">

            <h6 style="font-size: 1em; margin: 0px; color: #007bff;">{{ $booking->company->name }}</h6>

            <p style="margin-bottom: 0;">{{ $booking->company->address }}</p>

            <p style="margin-bottom: 0;"><strong>Complain Line:</strong> {{ $booking->company->phone }}</p>

            <p style="margin-bottom: 0;"><strong>Email:</strong> {{ $booking->company->email }}</p>

        </div>

        <div style="width: 48%;">

            <h6 style="font-size: 1em; margin: 0px; color: #007bff;">Booking Confirmation Invoice</h6>

            <p style="margin-bottom: 0;"><strong>Invoice Date:</strong> {{ date('d-m-Y', strtotime($booking->booking_date)) }}</p>

            <p style="margin-bottom: 0;"><strong>Invoice No:</strong> {{ $booking->booking_prefix . $booking->booking_number }}</p>

        </div>

    </div>
    <hr>

    <div style="margin-bottom: 20px;">

        <div style="width: 100%; border: 1px solid black; padding: 10px;">

            <div style="overflow-x: auto;">

                <table style="width: 100%; border-collapse: collapse;">

                    <thead>

                        <tr>

                            <th colspan="3" style="text-align: left; margin-bottom: 0; padding-bottom: 0;">

                                <h6 style="font-size: 1em; margin: 0px; margin: 5px 0px;">To,</h6>

                            </th>

                        </tr>

                        <tr style="border-bottom: 1px solid black;">

                            <th colspan="3" style="text-align: left; margin-bottom: 0; padding-bottom: 0;">

                                <h6 style="font-size: 1em; margin: 0px; margin: 5px 0px;">
                                    {{ $booking->passengers[0]->title . ' ' . $booking->passengers[0]->first_name . ' ' . $booking->passengers[0]->middle_name . ' ' . $booking->passengers[0]->last_name }}
                                </h6>

                            </th>

                        </tr>

                    </thead>

                    <tbody style="text-align: left; ">

                        <tr>

                            <th style="margin-bottom: 0; padding-bottom: 0;"><p style="margin:0px; padding:0px;">Booking Confirmation No (PNR)</p></th>

                            <td style="margin-bottom: 0; padding-bottom: 0;"><p style="margin:0px; padding:0px;">:</p></td>

                            <td style="margin-bottom: 0; padding-bottom: 0;"><p style="margin:0px; padding:0px;">{!! $booking->flight_pnr !!}</p></td>

                        </tr>

                        <tr>

                            <th><p style="margin:0px; padding:0px;">Agent Name</p></th>

                            <td><p style="margin:0px; padding:0px;">:</p></td>

                            <td><p style="margin:0px; padding:0px;">{{ $booking->user->name }}</p></td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div style="margin-bottom: 20px;">

        <h6 style="font-size: 1em; margin: 0px;">Flight Details</h6>

        <div style="width: 100%; border: 1px solid black; padding: 10px;">

            {{-- {!! $booking->flight_pnr !!} --}}

            @if (!empty($booking->flights[0]->air_line_name))

                @php

                    if (!empty(array_column($booking->flights->toArray(), 'gds_no')[0])) {
                        $gd_no_class = '';
                        $gd_no = implode(
                            ' , ',
                            array_filter(
                                array_unique(array_column($booking->flights->toArray(), 'gds_no')),
                            ),
                        );
                    } else {
                        $gd_no_class = 'd-none';
                        $gd_no = '';
                    }

                    if (!empty(array_column($booking->flights->toArray(), 'airline_locator')[0])) {
                        $airline_class = '';
                        $airline_loc = implode(
                            ' , ',
                            array_filter(
                                array_unique(
                                    array_column($booking->flights->toArray(), 'airline_locator'),
                                ),
                            ),
                        );
                    } else {
                        $airline_class = 'display:none';
                        $airline_loc = '';
                    }

                    if (!empty(array_column($booking->flights->toArray(), 'ticket_no')[0])) {
                        $ticket_no_class = '';
                        $ticket_no = implode(
                            ' , ',
                            array_filter(
                                array_unique(array_column($booking->flights->toArray(), 'ticket_no')),
                            ),
                        );
                    } else {
                        $ticket_no_class = 'display:none';
                        $ticket_no = '';
                    }
                    $f_airline_name = implode(
                        ' , ',
                        array_filter(
                            array_unique(array_column($booking->flights->toArray(), 'air_line_name')),
                        ),
                    );
                @endphp


                <table class="table" style="width: 100%; border-collapse: collapse;">

                    <tr class="bg-grey header" style="background: #e4e4e4; font-weight: bold;">
                        <td style="{{ $airline_class }}">
                            Airline Locator
                        </td>
                        <td style="{{ $ticket_no_class }}">
                            Ticket Number
                        </td>
                        <td>
                            AirLine
                        </td>

                    </tr>

                    <tr>

                        <td style='{{ $airline_class }}'>
                            <p>{{ $airline_loc }}</p>
                        </td>
                        <td style='{{ $ticket_no_class }}'>
                            <p> {{ $ticket_no }} </p>
                        </td>
                        <td>
                            <p>{{ $f_airline_name }}</p>
                        </td>
                    </tr>
                </table>

                <table class="table table-bordered" style="width: 100%; border-collapse: collapse;">

                    <tr class="bg-grey header" style="background: #e4e4e4; font-weight: bold;">
                        <td>
                            Flight No
                        </td>
                        <td>
                            Dep. Airport
                        </td>
                        <td>
                            Dep. Date / Time
                        </td>
                        <td>
                            Arr. Airport
                        </td>
                        <td>
                            Arr. Date / Time
                        </td>
                        <td>
                            Booking Class
                        </td>
                        <td>
                            Number of Baggage
                        </td>
                    </tr>
                    @foreach ($booking->flights as $flight)
                        <tr>
                            <td>
                                <p>{{ $flight->flight_number }}</p>
                            </td>
                            <td>
                                <p>{{ $flight->departure_airport }}</p>
                            </td>
                            <td>
                                <p>{{ date('d M Y', strtotime($flight->departure_date)) . ' - ' . date('H:i', strtotime($flight->departure_time)) }}
                                </p>
                            </td>
                            <td>
                                <p>{{ $flight->arrival_airport }}</p>
                            </td>
                            <td>
                                <p>{{ date('d M Y', strtotime($flight->arrival_date)) . ' - ' . date('H:i', strtotime($flight->arrival_time)) }}
                                </p>
                            </td>
                            <td>
                                <p>{{ $flight->booking_class }}</p>
                            </td>
                            <td>
                                <p>{{ $flight->number_of_baggage }}</p>
                            </td>
                        </tr>
                    @endforeach
                </table>

            @endif

        </div>



        @if (count($booking->hotels) > 0)

            <div style="width: 100%; margin-bottom: 20px;">

                <h6 style="font-size: 1em; margin: 0px;">Hotel Details</h6>
                <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">

                    <tr style="background-color: #f8f9fa;">

                        <td>

                            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0; padding-bottom: 0;">Hotel Name</h6>

                        </td>

                        <td>

                            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0; padding-bottom: 0;">Check In</h6>

                        </td>

                        <td>

                            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0; padding-bottom: 0;">Check Out</h6>

                        </td>

                        <td>

                            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0; padding-bottom: 0;">Nights</h6>

                        </td>

                        <td>

                            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0; padding-bottom: 0;">Meal Type</h6>

                        </td>

                        <td>

                            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0; padding-bottom: 0;">Room</h6>

                        </td>

                    </tr>

                    @foreach ($booking->hotels as $hotel)
                        <tr>
                            <td>
                                <p style="margin-bottom: 0; padding-bottom: 0;">{{ $hotel['hotel_name'] }}</p>
                            </td>
                            <td>
                                <p style="margin-bottom: 0; padding-bottom: 0;">{{ date('d M Y', strtotime($hotel['check_in_date'])) }}</p>
                            </td>
                            <td>
                                <p style="margin-bottom: 0; padding-bottom: 0;">{{ date('d M Y', strtotime($hotel['check_out_date'])) }}</p>
                            </td>
                            <td>
                                <p style="margin-bottom: 0; padding-bottom: 0;">{{ $hotel['total_nights'] }}</p>
                            </td>
                            <td>
                                <p style="margin-bottom: 0; padding-bottom: 0;">{{ $hotel['meal_type'] }}</p>
                            </td>
                            <td>
                                <p style="margin-bottom: 0; padding-bottom: 0;">{{ $hotel['room_type'] }}</p>
                            </td>
                        </tr>
                    @endforeach

                </table>

            </div>

        @endif

        @if (count($booking->transports) > 0)

            <div style="width: 100%; margin-bottom: 20px;">

                <h6 style="font-size: 1em; margin: 0px;"><strong>Transport</strong> Detail</h6>

                <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">

                    <tr style="background-color: #f8f9fa;">

                        <td>

                            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0; padding-bottom: 0;">Type</h6>

                        </td>

                        <td>

                            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0; padding-bottom: 0;">Transport Airport</h6>

                        </td>

                        <td>

                            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0; padding-bottom: 0;">Location</h6>

                        </td>

                        <td>

                            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0; padding-bottom: 0;">Time</h6>

                        </td>

                        <td>

                            <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0; padding-bottom: 0;">Vehicle Type</h6>

                        </td>

                    </tr>

                    @foreach ($booking->transports as $transport)
                        <tr>
                            <td>
                                <p style="margin-bottom: 0; padding-bottom: 0;">{{ $transport['transport_type'] }}</p>
                            </td>
                            <td>
                                <p style="margin-bottom: 0; padding-bottom: 0;">{{ $transport['airport'] }}</p>
                            </td>
                            <td>
                                <p style="margin-bottom: 0; padding-bottom: 0;">{{ $transport['location'] }}</p>
                            </td>
                            <td>
                                <p style="margin-bottom: 0; padding-bottom: 0;">{{ date('H:i', strtotime($transport['time'])) }}</p>
                            </td>
                            <td>
                                <p style="margin-bottom: 0; padding-bottom: 0;">{{ $transport['car_type'] }}</p>
                            </td>
                        </tr>
                    @endforeach

                </table>

            </div>

        @endif

    </div>

    <h6 style="font-size: 1em; margin: 0px; border-bottom: 1px solid black;">Passenger(s)</h6>

    <div style="margin-bottom: 20px;">

        <div style="width: 100%;">
            @foreach ($booking->passengers as $passenger)
                <h6 style="font-size: 1em; margin: 0px; margin-bottom: 0;">
                    {{ $passenger->title . ' ' . $passenger->first_name . ' ' . $passenger->middle_name . ' ' . $passenger->last_name }}
                </h6>
            @endforeach
        </div>

    </div>

    <h6 style="font-size: 1em; margin: 0px; border-bottom: 1px solid black; margin-top: 10px;">CHARGES DETAIL</h6>

    <div style="margin-bottom: 20px;">

        <div style="width: 100%;">

            <div class="table-responsive">

                <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">

                    <tbody style="text-align: left;">
                        @foreach ($booking->prices as $price)
                        @if ($price->quantity != null)
                            <tr>

                                <th style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">{{ $price->booking_type }} </p></th>

                                <th colspan="3" style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;"> {{ $price->quantity . ' * ' . $price->sale_cost }}</p></th>

                                <td style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">:</p></td>

                                <td style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">{{ $booking->currency . $price->total }}</p></td>

                            </tr>
                        @endif

                        @endforeach

                        @if (count($booking->otherCharges) > 0)
                            @foreach ($booking->otherCharges as $other_charge)
                                <tr>

                                    <th style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">{{ $other_charge->charges_type  }} </p></th>
                                    {{-- <th style="margin-bottom: 0px; padding-bottom: 0px;">{{ $other_charge->comments  }} </th> --}}

                                    <th colspan="3" style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;"></p></th>

                                    <td style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">:</p></td>

                                    <td style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">{{ $booking->currency . $other_charge->amount }}</p> </td>

                                </tr>
                            @endforeach
                        @endif



                        <tr>

                            <th style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">Total</p></th>

                            <th colspan="3" style="margin-bottom: 0px; padding-bottom: 0px;"></th>

                            <td style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">:</p></td>

                            <td style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">{{ $booking->currency . $booking->total_sales_cost }}</p> </td>

                        </tr>


                        <tr>

                            <th style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">Paid</p></th>

                            <th colspan="3" style="margin-bottom: 0px; padding-bottom: 0px;"></th>

                            <td style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">:</p></td>

                            <td style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">{{ $booking->currency . $booking->deposite_amount }}</p> </td>

                        </tr>

                        <tr>

                            <th style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">Balance Due</p></th>

                            <th colspan="3" style="margin-bottom: 0px; padding-bottom: 0px;"></th>

                            <td style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">:</p></td>

                            <td style="margin-bottom: 0px; padding-bottom: 0px;"><p style="margin:0px; padding:0px;">{{ $booking->currency . $booking->balance_amount }}</p> </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <h6 style="font-size: 1em; margin: 0px; border-bottom: 1px solid black; margin-top: 10px;">Payment(s)</h6>

    <div style="margin-bottom: 20px;">

        <div style="width: 100%;">

            <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">

                <thead style="text-align: left;">

                    <tr style="background-color: #f8f9fa;">

                        <th><p style="margin:0px; padding:0px;">Installment Number</p></th>

                        <th><p style="margin:0px; padding:0px;">Date</p></th>

                        <th><p style="margin:0px; padding:0px;">Mode</p></th>

                        <th><p style="margin:0px; padding:0px;">Amount</p></th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($booking->payments as $pay_key => $payment)

                        <tr>

                            <td><p style="margin:0px; padding:0px;">

                            @if ($payment->installment_no == 0 && empty($payment->other_charges_id))
                                        {{ 'Down Payment' }}
                                        @elseif (!empty($payment->other_charges_id))
                                        {{ 'Other Charges' }}
                                        @else
                                        {{$payment->installment_no}}
                                        @endif
                                    </p></td>

                            <td><p style="margin:0px; padding:0px;">{{ date('d M Y', strtotime($payment->payment_on)) }}</p></td>

                            <td><p style="margin:0px; padding:0px;">{{ $payment->payment_method }}</p></td>

                            <td><p style="margin:0px; padding:0px;">{{ $booking->currency . ' ' . number_format($payment->reciving_amount, 2) }} @if ($payment->cc_charges > 0)
                                {{ " (+ ".number_format($payment->cc_charges, 2) ." CC)"}}
                            @endif</p></td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <hr>

    <div style="margin-bottom: 20px;">
        <div style="width: 100%;">
            <h6 style="font-size: 1em; margin: 0px;"><strong>Please</strong> be advised that all payments should be deposited exclusively in the following banks.</h6>
            <div class="table-responsive">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">

                <tr style="background-color: #f8f9fa;">
                    <td>
                        <h6 style="font-size: 1em; margin: 0px;">Sr. #</h6>
                    </td>
                    <td>
                        <h6 style="font-size: 1em; margin: 0px;">Account Title</h6>
                    </td>
                    <td>
                        <h6 style="font-size: 1em; margin: 0px;">Account Number</h6>
                    </td>
                    <td>
                        <h6 style="font-size: 1em; margin: 0px;">Bank Name</h6>
                    </td>
                    <td>
                        <h6 style="font-size: 1em; margin: 0px;">Sort Code</h6>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p style="margin:0px; padding:0px;">1</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">SEVEN ZONES SERVICES LTD</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">33807991</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">BARCLAYS BANK</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">20-71-75</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin:0px; padding:0px;">2</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">SEVEN ZONES SERVICES LTD</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">18776308</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">METRO BANK</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">23-05-80</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin:0px; padding:0px;">3</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">SEVEN ZONES SERVICES LTD</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">18493597</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">TIDE BANK</p>
                    </td>
                    <td>
                        <p style="margin:0px; padding:0px;">04-06-05</p>
                    </td>
                </tr>

                </table>
            </div>
            <h6 style="font-size: 1em; margin: 0px;"><strong>Note: </strong> Payments made to any bank other than those listed above will not be accepted.</h6>
        </div>
    </div>
    <hr>
    @if ($booking->comments != null)
        <h6 style="font-size: 1em; margin: 0px; border-bottom: 1px solid black;">Booking Notes</h6>
        <div style="margin-bottom: 20px;">

            <div style="width: 100%;">

                {{ $booking->comments }}

            </div>

        </div>
    @endif

    <div style="margin-bottom: 20px;">

        <div style="width: 100%;">

            <h6 class="text-center" style="text-align: center;">Booking Terms & Conditions</h6>

            <table class="table">

                <tr>

                    <td>

                        <div style="margin-bottom: 20px;">

                            <div style="width: 100%;">

                                <ul style="columns: 2;">

                                    <li>First & Business class passengers are requested to check in at least 2 hrs before departure. </li>

                                    <li>Economy class passengers are requested to check in at least 3 hrs. Before your departure time at airport.</li>

                                    <li>Date Changes before departure and before inbound departure is not permitted</li>

                                    <li>Cancellation fees before /after departure or no shows or partly used tickets are non refundable</li>

                                    <li>Deposits are non-refundable and rights are non-changeable, non refundable from the point of confirmation</li>

                                    <li>We take deposits that secure your seats however; fares may change until the balance payment made or until issuance.</li>

                                    <li>Please pay balances when due because failure to do so may lead to the cancellation of your holiday/ights.</li>

                                    <li>And still leave you liable to pay cancellation charges.</li>

                                    <li>All credit/debit card payments are subject to a surcharge</li>

                                    <li>Booking has been placed with our consolidator</li>

                                    <li>This document is the confirmation of booking only</li>

                                    <li>Debit/credit card payment will be charged by SEVEN ZONES SERVICES LTD.</li>

                                </ul>

                                <br>

                                <ul>

                                    <li>Seven Zones would process an application in adherence to the client's unique preference(s) to our travel advisor(s) or as given on our website, and hence we will not be responsible for any incorrect information provided to us, through any medium. Although Seven Zones makes every best possible effort to obtain all the relevant and correct information within a specific period of time, however we deny to accept any responsibility, due to the following occurrences : </li>

                                    <ol>

                                        <li> (a) Refusal of Visa Application</li>

                                        <li> (b) Denial of Visa</li>

                                        <li> (c) Visa Issued with Incorrect passenger details</li>

                                        <li> (d) Delay in processing times of the Visa Application</li>

                                    </ol>

                                </ul>

                                <h6 style="font-size: 1em; margin: 0px; border-bottom: 1px solid black;"><b>Terms and Conditions</b></h6>


                                <ul>
                                    <li>Please note by replying "yes" or "All details are correct" or " I Agree ok " and any other confirmation to this email means you agree to our terms and conditions and the details or your booking including names, flight details and other package details are correct.</li>
                                    <li>All ticket(s) booked through us are strictly non-changeable and non-refundable.</li>

                                    <li>We take deposits that secure your seats however; fares may change until the balance payment made or until issuance.</li>

                                    <li>All upfront deposits are completely Non-Refundable.</li>

                                    <li>Please ensure that you must reply the email. Failure to reply the email would be considered as confirmation that all the details given on invoice regarding flight details and passenger details are correct. Furthermore company will not take any responsibility if there is any mistake if you fail to reply the email.</li>

                                    <li>Please note that it is the passenger's responsibility to ensure that the outstanding balance payment is paid within the Due date, wherein failure to do so would result in the cancellation of your booking and forfeiting of the upfront deposit money paid to us. Please ensure to update us about any payments that you make.</li>

                                    <li>Cancellations / Refunds are subject to the guidelines / restrictions imposed by the airline company less our administration charge. Please note that the refunds are processed in 7-8 business weeks, and no refunds would be paid out, till the same have been received from the relevant airline / supplier.</li>

                                    <li>All changes / amendments are subject to fare restrictions / availability of seats at the time of making an amendment.</li>

                                    <li>All quotes are subject to availability and are not guaranteed, until the ticket(s) have been issued, irrespective of the fact that the full payment has been made, as airline fares and seat availability changes on an ongoing basis.</li>

                                    <li>As airline carriers prefer e-ticket as the most preferred mode of travel, hence no paper ticket(s) will be issued.</li>

                                    <li>Passenger(s) are requested to re-confirm their booking(s) with either the travel agency 72 hrs prior to the flight departure time to obtain information about last minute changes, irrespective of the guidelines of the airline company.</li>

                                    <li>Passenger(s) are advised to check-in at least 3 hours prior to the flight departure time (for International Flights) and 2 hours prior to the flight departure time (for Domestic Flights).</li>

                                    <li>Please note that it is your responsibility to ensure that all your travel documents are correct and valid for travel. This includes your e-ticket, passport, visa (if required), travel insurance (if required) and any additional travel document(s).</li>

                                    <li>We accept no liability in the event wherein you are denied boarding or miss the flight or refused an entry into any country due to failure on your part to carry the correct passport, visa or other documents required by any airline, authority or country.</li>

                                    <li>Travellers must ensure that their passport is valid for at least 6 months, from their return date of travel.</li>

                                    <li>Passenger(s) travelling to The United States of America would need to apply for an ESTA Visa by visiting the U.S. Government website at: <a href="https://esta.cbp.dhs.gov/esta/" target="__new">https://esta.cbp.dhs.gov/esta/</a> .</li>

                                    <li>It is recommended that all the passenger(s) should be covered by a travel insurance policy to ensure protection against unforeseen situations. Please note that the travel insurance is not a part of your booking and hence needs to be purchased as a separate component. Please note that any claim made under the insurance policy would be governed by the guidelines of the insurance company and we would not be liable to accommodate any claims / request(s) from passenger(s) regarding the same.</li>

                                    <li>All transactions carried out on a credit card would attract a surcharge of 3% over and above the amount (which in case of a refund would be non-refundable), however transactions carried out on a debit card, would be free from any surcharge. It may also be noted that documents such as copies of passport(s) / card(s) through which the payment was made could be requested, against unsuccessful / unauthorized payments, in order to verify the genuinity of the cardholder.</li>
                                    @if ($booking->company->id == 6)
                                        <li>All the authorized payments taken by Seven Zones which is the parent company of HajjUmrah4U.</li>
                                    @endif


                                </ul>

                            </div>

                        </div>

                    </td>

                </tr>

            </table>

        </div>

    </div>

    <div style="margin-top: 20px;">

        <h5 style="font-size: 1em; margin-bottom: 10px; color: #007bff;">Thanks & Regards,</h5>

        <h6 style="font-size: 1em; margin: 0px; margin-bottom: 10px; color: #007bff;">{{ $booking->user->name }}</h6>
        <h6 style="font-size: 1em; margin: 0px; margin-bottom: 10px;">Sales Department</h6>

        <p style="margin-bottom: 0;">{{ $booking->company->name }}</p>

        <p style="margin-bottom: 0; margin-top:0px;">{{ $booking->company->address }}</p>

        <p style="margin-bottom: 0; margin-top:0px;"><strong>Complain Line:</strong> {{ $booking->company->phone }}</p>

        <p style="margin-bottom: 0; margin-top:0px;"><strong>Email:</strong> {{ $booking->company->email }}</p>

    </div>

</body>
</html>
