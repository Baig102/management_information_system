  <!doctype html>
  <html class="no-js " lang="en">

  <head>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <style type="text/css">
          .border_bottom {
              border-bottom: 1px solid lightgrey
          }

          .bg_grey {
              background-color: lightgrey !important;
              color: black !important
          }

          /* @font-face {
            font-family: 'Elegance';
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
            src: url("http://eclecticgeek.com/dompdf/fonts/Elegance.ttf") format("truetype");
          } */

          /* @media print { */
          body {
              margin: 0;
              padding: 0;
              font: 12px normal Arial, Helvetica, sans-serif;
              -webkit-print-color-adjust: exact !important;
          }

          table p,
          h6 {
              font-size: 12px !important;
              margin-bottom: 0px !important;
              text-transform: uppercase !important;
          }

          h4 {
              font-size: 16px !important;
              font: 12px normal Arial, Helvetica, sans-serif;
          }

          .bg-grey {
              background-color: lightgrey !important;
          }

          .no-border {
              border: 0px !important;
          }

          .pagebreak {
              page-break-before: always;
          }

          /* page-break-after works, as well */
          .before {
              page-break-before: always;
          }

          .after {
              page-break-after: always;
          }

          .avoid {
              page-break-inside: avoid;
          }

          .header td {
              background-color: #e4e4e4 !important;
          }

          .header td {
              background-color: #e4e4e4 !important;
              color: #000 !important;
          }

          .table td,
          .table th {
              padding: 0.15rem !important;
          }

          .invoice_header td {
              border-top: 0px !important;
          }

          .invoice_header_tr td {
              border-bottom: 1px solid #000;
              /*padding-bottom: 5px;*/
          }

          .invoice_header_tr2 td h6 {
              /*padding: 0.30rem!important;*/
              margin-top: 10px !important;
          }

          .table-bordered td,
          .table-bordered th {
             /* border: 1px solid #000 !important; */
              border: 0px solid #000 !important;
          }

          /* } */

          @media print {
              div.divFooter {
                  position: fixed;
                  bottom: 0;
              }

              #print_button {
                  display: none;
              }

              #go_back {
                  display: none;
              }

              /*div.divHeader {
          position: relative;
          top: 0;
        }*/
          }
      </style>
  </head>

  <body class="theme-blush"> {{-- onload="window.print()" --}}

      <div class="container-fluid">
          <div class="row clearfix">
              <div class="col-lg-12">

                <table class="table no-border">
                    <tr>
                        <td style="border-top: none!important;">

                            <table class="table invoice_header">
                                <tbody>
                                    <tr class="invoice_header_tr">
                                        <td width="30%">
                                            <img src="{{ asset('images/companyLogos/' . $booking->company->logo) }}" width="150">
                                        </td>
                                        <td class="text-center" width="40%">
                                            <h4 class='mt-2' style="font-weight: 800;">ELECTRONIC TICKET RECORD</h4>
                                        </td>
                                        <td class="text-right" width="30%">
                                            <h4 class='mt-2'>Booking Ref:
                                                <span style="font-weight: 800;">{{ $booking->booking_prefix }}{{ $booking->booking_number }}</span></h4>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered " id="">

                                <tr class="bg-grey header border-dark td-border">
                                    <td width="20">
                                        <h6>SR# </h6>
                                    </td>
                                    <td width="20">
                                        <h6>Title </h6>
                                    </td>
                                    <td width="40">
                                        <h6>First Name </h6>
                                    </td>
                                    <td width="40">
                                        <h6>Mid Name </h6>
                                    </td>
                                    <td width="40">
                                        <h6>Last Name </h6>
                                    </td>
                                    <td width="40">
                                        <h6>Ticket Number</h6>
                                    </td>
                                </tr>
                                @foreach ($booking->passengers as $pass_key => $passenger)
                                    <tr class="border-dark td-border">
                                        <td>
                                            <p>{{ $pass_key+1 }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $passenger->title }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $passenger->first_name }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $passenger->middle_name }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $passenger->last_name }}</p>
                                        </td>
                                        <td>
                                            <p>{{ $passenger->ticket_number }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            @if (!empty($booking->flights[0]->air_line_name))

                                <h6><strong>Flight </strong> Details</h6>

                                <table class="table table-bordered">

                                    <tr class="bg-grey header">
                                        <td>
                                            <h6>Flight No</h6>
                                        </td>
                                        <td>
                                            <h6>From</h6>
                                        </td>
                                        <td>
                                            <h6>To</h6>
                                        </td>
                                        <td>
                                            <h6>Departure</h6>
                                        </td>
                                        <td>
                                            <h6>Arrival</h6>
                                        </td>
                                        <td>
                                            <h6>Booking Class</h6>
                                        </td>
                                        <td>
                                            <h6>Number of Baggage</h6>
                                        </td>
                                        <td>
                                            <h6>Status</h6>
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
                                                <p>{{ $flight->arrival_airport }}</p>
                                            </td>
                                            <td>
                                                <p>{{ date('d M Y', strtotime($flight->departure_date)) . ' - ' . date('H:i', strtotime($flight->departure_time)) }}
                                                </p>
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
                                            <td>
                                                <p>OK</p>
                                            </td>
                                        </tr>

                                        <tr class="bg-grey header">
                                            <td colspan="4">
                                                <h6><b>Airline:</b> {{$flight->air_line_name}}</h6>
                                            </td>
                                            <td colspan="4">
                                                <h6><b>Airline Ref. No:</b> {{$flight->airline_locator}}</h6>
                                            </td>
                                        </tr>

                                         <!-- Add an empty row for spacing between flights -->
                                        @if (!$loop->last)
                                            <tr class="empty-row">
                                                <td colspan="8" style="height: 10px;"></td> <!-- You can adjust the height as needed -->
                                            </tr>
                                        @endif
                                    @endforeach

                                </table>
                            @endif

                        </td>
                    </tr>
                </table>

                {{-- <div style="break-after: page;"></div> --}}
                <table class="table">
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h6 class="border_bottom mb-2"><b>Terms and Conditions & Important Travel Information</b></h6>
                                        <p>To ensure a smooth and enjoyable journey, please note the following essential details:</p>
                                        <ul>
                                            <li>Please report to the check-in desk at least <b>3 hours</b> prior to your scheduled departure time. Additionally, we recommend reconfirming your outbound and inbound flights directly with the airline at least <b>72 hours before departure</b>. Alternatively, you can contact our dedicated customer care team on <b>02079932994</b> for assistance.</li>
                                            <li>Please be aware that tickets must be used in the <b>sequence outlined</b> in your itinerary</li>
                                            <li>In the event of <b>overbooking</b>, we advise <b>checking in online</b> and <b>selecting your seat</b> in advance to ensure a smooth journey and secure your preferred seat.</li>
                                            <li>Please note that the Electronic Ticket record may not reflect any <b>changes, schedule updates</b>, or <b>cancellations</b> made after the electronic ticket was issued. For the most <b>up-to-date</b> and accurate flight information, please contact the airline directly at least <b>48 hours</b> prior to your flight or contact our customer care team for assistance.</li>
                                            <li>Please note that the Electronic Ticket record may not reflect any <b>changes</b>, <b>schedule updates</b>, or <b>cancellations</b> made after the electronic ticket was issued. For the most <b>up-to-date</b> and accurate flight information, please contact the airline directly at least <b>48 hours</b> prior to your flight or contact our customer care team for assistance.</li>

                                            <li>For flights with a <b>piece-based baggage allowance</b> (PC), each individual piece of baggage <b>must not exceed 23kg</b>. Infants travelling with an accompanying adult are entitled to a maximum baggage allowance of 10kg.</li>
                                            <li>A copy of the <b>terms and conditions</b> of travel, as well as the Carrier Liability Notice, can be obtained from our travel agency or the transporting carrier upon request.</li>
                                            <li>All departure and arrival times are displayed in <b>local time</b>.</li>
                                        </ul>
                                        <p><strong>We hope this information helps ensure a pleasant and hassle-free travel experience. If you have any further questions or concerns, please do not hesitate to contact us.</strong></p>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

                <div class="divFooter row" style="margin: 0 auto;width: 100%;">
                    <table class="table">
                        <tr>
                            <td><img src="{{ asset('images/atol.png') }}" width="100"></td>
                            <td>
                                <div class="text-center">
                                    <p class="mb-0"><b>Seven Zones Travel: 9 Station Road, West Drayton, UB7 7BT</b>
                                    </p>
                                    <p class="mb-0"><b>Tel: 02079932994; Email: sales@sevenzones.co.uk; URL:
                                            www.sevenzones.co.uk</b></p>
                                    <p class="mb-0">Seven Zones Services Limited England & Wales. Company
                                        Registration No. 09784212</p>
                                    <p class="mb-0"><b>Agents for ATOL and IATA holders</b></p>
                                </div>
                            </td>
                            <td><img src="{{ asset('images/iata-logo-new.png') }}" width="100"></td>
                        </tr>
                    </table>

                </div>

              </div>

          </div>
          {{-- <button class="btn btn-primary btn-sm" id="print_button" onClick="window.print()"> <i class="zmdi zmdi-print"></i> PRINT </button>
          <a href="#" class="btn bg-info btn-sm" id="go_back"> <i class="zmdi zmdi-undo"></i> Go Back </a> --}}
      </div>
  </body>

  </html>
