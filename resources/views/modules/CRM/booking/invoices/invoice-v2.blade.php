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

                  @php
                    //echo '<pre>'; print_r($booking); echo '</pre>'; //exit;
                      // Convert the refunds array to a collection
                      $refundsCollection = collect($booking->refunds);
                      // Filter the collection where refund_status is 1
                      $filteredRefunds = $refundsCollection->where('refund_status', 1);
                      // Calculate the total refunded_amount
                      $totalRefundedAmount = $filteredRefunds->sum('refunded_amount');
                      // Calculate the total service_charges
                      $totalServiceCharges = $filteredRefunds->sum('service_charges');

                  @endphp
                  @if ($booking->refunded_amount > 0)
                  @endif
                  <h1 class="position-absolute text-danger text-uppercase display-4" style="z-index: 99999; top: 5%; left: 30%; opacity: 0.5;"> Internal Use Only</h1> {{-- top-50 position-absolute rotate: -45deg;  --}}

                  <table class="table no-border">
                      <tr>
                          <td style="border-top: none!important;">

                              <table class="table invoice_header">
                                  <tbody>
                                      <tr class="invoice_header_tr">
                                          <td width="30%">
                                              <img src="{{ asset('images/companyLogos/' . $booking->company->logo) }}"
                                                  width="150">
                                          </td>
                                          <td class="text-center" width="40%">
                                              <h4 class='mt-2'>INVOICE / RECEIPT FOR INTERNAL USER ONLY</h4>
                                          </td>
                                          <td class="text-right" width="30%">
                                              <h4 class='mt-2'>Invoice No:
                                                  {{ $booking->booking_prefix }}{{ $booking->booking_number }}</h4>
                                          </td>
                                      </tr>
                                      <tr class="invoice_header_tr2">

                                          @if ($booking->business_customer_id != null)
                                            <td colspan="2">
                                                <h6 class="m-0">Customer: {{ $booking->businessCustomer->name }}</h6>
                                            </td>
                                        @else
                                            <td colspan="2">
                                              <h6 class="m-0">Name: {{ $booking->passengers[0]->title }}
                                                  {{ $booking->passengers[0]->first_name }}
                                                  {{ $booking->passengers[0]->middle_name }}
                                                  {{ $booking->passengers[0]->last_name }}</h6>
                                          </td>
                                        @endif
                                          <td>
                                              <h6 class="m-0">Invoice date:
                                                  {{ date('d M Y', strtotime($booking->booking_date)) }}</h6>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td colspan="2">
                                            @if ($booking->business_customer_id == null)
                                                <h6>Address: C/o {{ $booking->passengers[0]->address }} - Post Code:
                                                  {{ $booking->passengers[0]->post_code }} </h6>
                                            @endif
                                          </td>
                                          <td>
                                              <h6>Sales Agent: {{ $booking->user->name }}</h6>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td colspan="2">
                                              @if ($booking->business_customer_id == null)
                                                <h6>Tel: {{ $booking->passengers[0]->mobile_number }}</h6>
                                            @endif
                                          </td>
                                          <td>
                                              <h6>Payment Status:

                                                  @if ($booking->payment_status == 1)
                                                      <span class="text-danger"><i
                                                              class="ri-close-circle-line fs-17 align-middle"></i>
                                                          Pending </span>
                                                  @elseif ($booking->payment_status == 2)
                                                      <span class="text-success"><i
                                                              class="ri-checkbox-circle-line fs-17 align-middle"></i>
                                                          Fully Paid </span>
                                                  @elseif ($booking->payment_status == 3)
                                                      <span class="text-danger"><i
                                                              class="ri-close-circle-line fs-17 align-middle"></i>
                                                          Balance Pending </span>
                                                  @elseif ($booking->payment_status == 4)
                                                      <span class="text-warning"><i
                                                              class="ri-checkbox-circle-line fs-17 align-middle"></i>
                                                          Partially Refunded </span>
                                                  @elseif ($booking->payment_status == 5)
                                                      <span class="text-warning"><i
                                                              class="ri-checkbox-circle-line fs-17 align-middle"></i>
                                                          Fully Refunded </span>
                                                  @endif
                                              </h6>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td colspan="2">
                                              @if ($booking->business_customer_id == null)
                                                <h6>Email: {{ $booking->passengers[0]->email }}</h6>
                                            @endif
                                          </td>


                                      </tr>
                                  </tbody>
                              </table>
                              <hr>
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
                                  </tr>
                                  @foreach ($booking->passengers as $pass_key => $passenger)
                                      <tr class="border-dark td-border">
                                          <td>
                                              <p>{{ $pass_key + 1 }}</p>
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
                                      </tr>
                                  @endforeach
                              </table>
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
                                          $airline_class = 'd-none';
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
                                          $ticket_no_class = 'd-none';
                                          $ticket_no = '';
                                      }
                                      $f_airline_name = implode(
                                          ' , ',
                                          array_filter(
                                              array_unique(array_column($booking->flights->toArray(), 'air_line_name')),
                                          ),
                                      );
                                  @endphp

                                  <h6><strong>Flight </strong> Details</h6>

                                  <table class="table table-bordered">

                                      <tr class="bg-grey header">
                                          <td class="{{ $airline_class }}">
                                              <h6>Airline Locator</h6>
                                          </td>
                                          <td class="{{ $ticket_no_class }}">
                                              <h6>Ticket Number</h6>
                                          </td>
                                          <td>
                                              <h6>AirLine</h6>
                                          </td>
                                          <td>
                                              <h6>Ticket Supplier</h6>
                                          </td>

                                      </tr>

                                      <tr>

                                          <td class='{{ $airline_class }}'>
                                              <p>{{ $airline_loc }}</p>
                                          </td>
                                          <td class='{{ $ticket_no_class }}'>
                                              <p> {{ $ticket_no }} </p>
                                          </td>
                                          <td>
                                              <p>{{ $f_airline_name }}</p>
                                          </td>
                                          <td>
                                              <p>{{ $booking->ticket_supplier }}</p>
                                          </td>
                                      </tr>
                                  </table>

                                  <table class="table table-bordered">

                                      <tr class="bg-grey header">
                                          <td>
                                              <h6>Flight No</h6>
                                          </td>
                                          <td>
                                              <h6>Dep. Airport</h6>
                                          </td>
                                          <td>
                                              <h6>Dep. Date / Time</h6>
                                          </td>
                                          <td>
                                              <h6>Arr. Airport</h6>
                                          </td>
                                          <td>
                                              <h6>Arr. Date / Time</h6>
                                          </td>
                                          <td>
                                              <h6>Booking Class</h6>
                                          </td>
                                          <td>
                                              <h6>Number of Baggage</h6>
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
                              @if (!empty($booking->hotels[0]['hotel_name']))

                                  <h6><strong>Hotel </strong> Details</h6>

                                  <table class="table table-bordered">

                                      <tr class="bg-grey header">
                                          <td>
                                              <h6>Hotel Name</h6>
                                          </td>
                                          <td>
                                              <h6>Check In</h6>
                                          </td>
                                          <td>
                                              <h6>Check Out</h6>
                                          </td>
                                          <td>
                                              <h6>Nights</h6>
                                          </td>
                                          <td>
                                              <h6>Meal Type</h6>
                                          </td>
                                          <td>
                                              <h6>Room</h6>
                                          </td>
                                          <td>
                                              <h6>Supplier</h6>
                                          </td>
                                      </tr>
                                      @foreach ($booking->hotels as $hotel)
                                          <tr>
                                              <td>
                                                  <p>{{ $hotel->hotel_name }}</p>
                                              </td>
                                              <td>
                                                  <p>{{ date('d M Y', strtotime($hotel->check_in_date)) }}</p>
                                              </td>
                                              <td>
                                                  <p>{{ date('d M Y', strtotime($hotel->check_out_date)) }}</p>
                                              </td>
                                              <td>
                                                  <p>{{ $hotel->total_nights }}</p>
                                              </td>
                                              <td>
                                                  <p>{{ $hotel->meal_type }}</p>
                                              </td>
                                              <td>
                                                  <p>{{ $hotel->room_type }}</p>
                                              </td>
                                              <td>
                                                  <p>{{ $hotel->supplier }}</p>
                                              </td>
                                          </tr>
                                      @endforeach
                                  </table>
                              @endif

                              @if (!empty($booking->transports[0]['transport_type']))

                                  <h6><strong>Transport</strong> Detail</h6>
                                  <table class="table table-bordered">

                                      <tr class="bg-grey header">
                                          <td>
                                              <h6>Type</h6>
                                          </td>
                                          <td>
                                              <h6>Transport Airport</h6>
                                          </td>
                                          <td>
                                              <h6>Location</h6>
                                          </td>
                                          <td>
                                              <h6>Time</h6>
                                          </td>
                                          <td>
                                              <h6>Vehical Type</h6>
                                          </td>
                                          <td>
                                            <h6>Supplier</h6>
                                        </td>
                                      </tr>
                                      @foreach ($booking->transports as $transport)
                                          <tr>
                                              <td>
                                                  <p>{{ $transport->transport_type }}</p>
                                              </td>
                                              <td>
                                                  <p>{{ $transport->airport }}</p>
                                              </td>
                                              <td>
                                                  <p>{{ $transport->location }}</p>
                                              </td>
                                              <td>
                                                <p>{{date("d M Y", strtotime($transport->transport_date))." ".date('H:i', strtotime($transport->time)) }}</p>
                                                </td>
                                              <td>
                                                  <p>{{ $transport->car_type }}</p>
                                              </td>
                                              <td>
                                                  <p>{{ $transport->supplier }}</p>
                                              </td>
                                          </tr>
                                      @endforeach
                                  </table>
                              @endif

                            <h6><strong>Charges</strong> Detail</h6>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Sale Cost</th>
                                        <th>Net Cost</th>
                                        <th>Quantity</th>
                                        <th>Total Sale Cost</th>
                                        <th>Agent Net Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($booking->prices as $flight )
                                        <tr class="{{ ($flight->booking_type == 'Other Charges') ? 'd-none' : '' }}">
                                            <th scope="row">{{$flight->booking_type}}</th>
                                            <td>{{ $booking->currency }}{{ number_format($flight->sale_cost, 2)}}</td>
                                            <td>{{ $booking->currency }}{{ number_format($flight->net_cost, 2)}}</td>
                                            <td>{{$flight->quantity}}</td>
                                            <td>{{ $booking->currency }}{{ number_format($flight->total, 2)}}</td>
                                            <td>{{ $booking->currency }}{{ number_format($flight->net_total, 2)}}</td>
                                        </tr>
                                     @endforeach



                                    <tr>
                                        <th scope="row" colspan="5"><span style="float: right;">Total Invoice Amount</span></th>
                                        <td>{{ $booking->currency }}{{ number_format($booking->total_sales_cost, 2)}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="5"><span style="float: right;">Net Price</span></th>
                                        <td>{{ $booking->currency }}{{ number_format($booking->total_net_cost, 2)}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="5"><span style="float: right;">Margin</span></th>
                                        <td>{{ $booking->currency }}{{ number_format($booking->total_sales_cost-$booking->total_net_cost, 2)}}</td>
                                    </tr>
                                </tbody>
                            </table>

                              <h6><strong>Other Charges</strong> Detail</h6>

                              <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Sr #</th>
                                        <th scope="col">Charges Type</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Receiving Amount</th>
                                        <th scope="col">Remaining Amount</th>
                                        <th scope="col">Comments</th>
                                        <th scope="col">Added On</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    @foreach ($booking->otherCharges as $oc_key => $otherCharge)
                                    <tr>
                                        <td>{{ $oc_key+1 }}</td>
                                        <td>{{ $otherCharge->charges_type }}</td>
                                        <td>{{ $booking->currency . ' ' . number_format($otherCharge->amount, 2) }}</td>
                                        <td>{{ $booking->currency . ' ' . number_format($otherCharge->reciving_amount, 2) }}</td>
                                        <td>{{ $booking->currency . ' ' . number_format($otherCharge->remaining_amount, 2) }}</td>
                                        <td>{{ $otherCharge->comments }}</td>
                                        <td>{{ $otherCharge->charges_at }}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>


                              <h6><strong>Payment</strong> Detail</h6>

                              <table class="table table-bordered">

                                  <tr class="bg-grey header">
                                      <td>
                                          <h6>Installment # </h6>
                                      </td>
                                      <td>
                                          <h6>Transactions</h6>
                                      </td>
                                      <td>
                                          <h6>Transactions Date</h6>
                                      </td>
                                      <td>
                                          <h6>Balance</h6>
                                      </td>
                                      <td>
                                          <h6>Due Date</h6>
                                      </td>
                                  </tr>
                                  @foreach ($booking->payments as $payment)
                                      @php

                                      @endphp
                                      <tr>
                                          <td>
                                              <p>
                                                  @if ($payment->installment_no == 0 && $payment->other_charges_id == null)
                                                      {{ 'Initial Payment' }}
                                                  @elseif($payment->other_charges_id != null)
                                                      {{ $payment->otherCharges->charges_type }}
                                                  @else
                                                      {{ $payment->installment_no }}
                                                  @endif
                                              </p>
                                          </td>
                                          <td>
                                              <p>{{ $booking->currency . ' ' . $payment->reciving_amount }}</p>
                                          </td>
                                          <td>
                                              <p>{{ $payment->deposit_date != null ? date('d M Y', strtotime($payment->deposit_date)) : '-' }}
                                              </p>
                                          </td>
                                          <td>
                                              <p>{{ $booking->currency . ' ' . $payment->remaining_amount }}</p>
                                          </td>
                                          <td>
                                              <p> {{ $payment->remaining_amount == 0 ? 'Payment Completed' : '' }}
                                                  {{-- date('d M Y', strtotime($payment->due_date)) --}}
                                              </p>
                                          </td>
                                      </tr>
                                  @endforeach
                              </table>

                              {{-- @if (count($booking->refunds) > 0) --}}
                              @if ($booking->refunds->where('refund_status', 1)->count() > 0)
                                  <h6><strong>Refund</strong> Breakdown</h6>

                                  <table class="table table-bordered">

                                      <tr class="bg-grey header">
                                          <td>
                                              <h6>Date</h6>
                                          </td>
                                          <td>
                                              <h6>Refund Type</h6>
                                          </td>
                                          <td>
                                              <h6>Total Received</h6>
                                          </td>
                                          <td>
                                              <h6>Admin Charges</h6>
                                          </td>
                                          <td>
                                              <h6>Refunded Amount</h6>
                                          </td>
                                      </tr>
                                      @foreach ($booking->refunds->where('refund_status', 1) as $refund)
                                          {{-- <pre> {{ print_r($installment) }} </pre> --}}
                                          <tr>
                                              <td>
                                                  <p>{{ date('d M Y', strtotime($refund->refund_requeseted_on)) }}</p>
                                              </td>
                                              <td>
                                                  <p> {{ str_replace('booking', '', $refund->refund_type) . ' Refund' }}
                                                  </p>
                                              </td>
                                              <td>
                                                  <p> {{ $refund->paid_amount }} </p>
                                              </td>
                                              <td>
                                                  <p> {{ $refund->service_charges }} </p>
                                              </td>
                                              <td>
                                                  <p>{{ $refund->refunded_amount }}</p>
                                              </td>
                                          </tr>
                                      @endforeach
                                  </table>
                                  <hr>
                              @endif


                              @if (array_sum(array_column($booking->installmentPlan->toArray(), 'amount')) > 0)

                                  <h6><strong>Outstanding</strong> Breakdown</h6>

                                  <table class="table table-bordered">

                                      <tr class="bg-grey header">
                                          <td>
                                              <h6>Installment # </h6>
                                          </td>
                                          <td>
                                              <h6>Transactions</h6>
                                          </td>
                                          <td>
                                              <h6>Transactions Date</h6>
                                          </td>
                                          <td>
                                              <h6>Balance</h6>
                                          </td>
                                          <td>
                                              <h6>Due Date</h6>
                                          </td>
                                      </tr>
                                      @foreach ($booking->installmentPlan as $installment)
                                          {{-- <pre> {{ print_r($installment) }} </pre> --}}
                                          <tr>
                                              <td>
                                                  <p><span class='text-danger'> {{ $installment->installment_number }}
                                                      </span></p>
                                              </td>
                                              <td>
                                                  <p> {!! $installment->is_received == 0
                                                      ? "<span class='text-danger'>" . $booking->currency . ' 0 </span>'
                                                      : $booking->currency . ' ' . $installment->amount !!}
                                                  </p>
                                              </td>
                                              <td>
                                                  <p>{!! $installment->is_received == 0
                                                      ? "<span class='text-danger'>Pending</span>"
                                                      : date('d M Y', strtotime($installment->received_on)) !!}
                                                  </p>
                                              </td>
                                              <td>
                                                  <p>{!! $installment->is_received == 0
                                                      ? "<span class='text-danger'>" . $booking->currency . ' ' . $installment->amount . '</span>'
                                                      : $booking->currency . ' ' . ' 0' !!}
                                                  </p>
                                              </td>
                                              <td>
                                                  {{-- <p> {!! "<span class='text-danger'>" . date('d M Y', strtotime($installment['due_date'])) . '</span>' !!} </p> --}}
                                                  <p> {{ date('d M Y', strtotime($installment['due_date'])) }} </p>
                                              </td>
                                          </tr>
                                      @endforeach
                                  </table>
                                  <hr>
                              @endif

                              <h6 class="text-primary"><strong>** Please</strong> be advised that all payments should be
                                  deposited exclusively in the following banks. <strong>**</strong></h6>

                              <table class="table table-bordered">

                                  <tr class="bg-grey header">
                                      <td>
                                          <h6>Sr. #</h6>
                                      </td>
                                      <td>
                                          <h6>Account Title</h6>
                                      </td>
                                      <td>
                                          <h6>Account Number</h6>
                                      </td>
                                      <td>
                                          <h6>Bank Name</h6>
                                      </td>
                                      <td>
                                          <h6>Sort Code</h6>
                                      </td>
                                  </tr>

                                  <tr>
                                      <td>
                                          <p>1</p>
                                      </td>
                                      <td>
                                          <p>SEVEN ZONES SERVICES LTD</p>
                                      </td>
                                      <td>
                                          <p>14530662</p>
                                      </td>
                                      <td>
                                          <p>LLOYDS BANK</p>
                                      </td>
                                      <td>
                                          <p>30-99-50</p>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <p>2</p>
                                      </td>
                                      <td>
                                          <p>SEVEN ZONES SERVICES LIMITED</p>
                                      </td>
                                      <td>
                                          <p>18493597</p>
                                      </td>
                                      <td>
                                          <p>TIDE BANK</p>
                                      </td>
                                      <td>
                                          <p>04-06-05</p>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <p>3</p>
                                      </td>
                                      <td>
                                          <p>SEVEN ZONES SERVICES LIMITED</p>
                                      </td>
                                      <td>
                                          <p>55043860</p>
                                      </td>
                                      <td>
                                          <p>Revolut Bank</p>
                                      </td>
                                      <td>
                                          <p>04-29-09</p>
                                      </td>
                                  </tr>

                              </table>
                              <h6><strong>Note: </strong> Payments made to any bank other than those listed above will
                                  not be accepted.</h6>
                          </td>
                      </tr>
                      @if (!empty($booking->comments))
                          <tr>
                              <td colspan="7">
                                  <h6>Booking Notes</h6>
                                  <p>{{ $booking->comments }}</p>
                              </td>
                          </tr>
                      @endif

                  </table>

                  <div style="break-after: page;"></div>

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
