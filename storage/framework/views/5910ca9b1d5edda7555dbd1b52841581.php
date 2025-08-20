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

  <body class="theme-blush"> 

      <div class="container-fluid">
          <div class="row clearfix">
              <div class="col-lg-12">

                <?php
                    // Convert the refunds array to a collection
                    $refundsCollection = collect($booking->refunds);
                    // Filter the collection where refund_status is 1
                    $filteredRefunds = $refundsCollection->where('refund_status', 1);
                    // Calculate the total refunded_amount
                    $totalRefundedAmount = $filteredRefunds->sum('refunded_amount');
                    // Calculate the total service_charges
                    $totalServiceCharges = $filteredRefunds->sum('service_charges');

                    $total_remaining_other_charges = $booking->otherCharges->sum('remaining_amount');
                    $total_balance_pending_amount = $booking->balance_amount+$total_remaining_other_charges;

                ?>
                <?php if($booking->refunded_amount > 0): ?>
                     
                <?php endif; ?>

                

                <table class="table no-border">
                    <tr>
                        <td style="border-top: none!important;">

                            <table class="table invoice_header">
                                <tbody>
                                    <tr class="invoice_header_tr">
                                        <td width="30%">
                                            <img src="<?php echo e(asset('images/companyLogos/' . $booking->company->logo)); ?>"
                                                width="250">
                                        </td>
                                        <td class="text-center" width="40%">
                                            <h4 class='mt-2' style="font-weight: 800;">INVOICE / RECEIPT</h4>
                                        </td>
                                        <td class="text-right" width="30%">
                                            <h4 class='mt-2'>Invoice No:
                                                <span style="font-weight: 800;"><?php echo e($booking->booking_prefix); ?><?php echo e($booking->booking_number); ?></span></h4>
                                        </td>
                                    </tr>
                                    <tr class="invoice_header_tr2">

                                        <?php if($booking->business_customer_id != null): ?>
                                            <td colspan="2">
                                                <h6 class="m-0">Customer: <?php echo e($booking->businessCustomer->name); ?></h6>
                                            </td>
                                        <?php else: ?>
                                            <td colspan="2">
                                                <h6 class="m-0">Name: <?php echo e($booking->passengers[0]->title); ?>

                                                    <?php echo e($booking->passengers[0]->first_name); ?>

                                                    <?php echo e($booking->passengers[0]->middle_name); ?>

                                                    <?php echo e($booking->passengers[0]->last_name); ?></h6>
                                            </td>
                                        <?php endif; ?>

                                        <td>
                                            <h6 class="m-0">Invoice date:
                                                <?php echo e(date('d M Y', strtotime($booking->booking_date))); ?></h6>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <?php if($booking->business_customer_id == null): ?>
                                                <h6>Address: C/o <?php echo e($booking->passengers[0]->address); ?> - Post Code:
                                                    <?php echo e($booking->passengers[0]->post_code); ?> </h6>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <h6>Sales Agent: <?php echo e($booking->user->name); ?></h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php if($booking->business_customer_id == null): ?>
                                                <h6>Tel: <?php echo e($booking->passengers[0]->mobile_number); ?></h6>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <h6>Payment Status:
                                                <?php if($total_balance_pending_amount > 0): ?>
                                                    <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Balance Pending </span>
                                                <?php else: ?>

                                                    <?php if($booking->payment_status == 1): ?>
                                                        <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Pending </span>
                                                    <?php elseif($booking->payment_status == 2): ?>
                                                        <span class="text-success"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Paid </span>
                                                    <?php elseif($booking->payment_status == 3): ?>
                                                        <span class="text-danger"><i class="ri-close-circle-line fs-17 align-middle"></i> Balance Pending </span>
                                                    <?php elseif($booking->payment_status == 4): ?>
                                                        <span class="text-warning"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Partially Refunded </span>
                                                    <?php elseif($booking->payment_status == 5): ?>
                                                        <span class="text-warning"><i class="ri-checkbox-circle-line fs-17 align-middle"></i> Fully Refunded </span>
                                                    <?php endif; ?>

                                                <?php endif; ?>

                                            </h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php if($booking->business_customer_id == null): ?>
                                                <h6>Email: <?php echo e($booking->passengers[0]->email); ?></h6>
                                            <?php endif; ?>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                            <h6><strong>Passenger </strong> Details</h6>
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
                                <?php $__currentLoopData = $booking->passengers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pass_key => $passenger): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-dark td-border">
                                        <td>
                                            <p><?php echo e($pass_key+1); ?></p>
                                        </td>
                                        <td>
                                            <p><?php echo e($passenger->title); ?></p>
                                        </td>
                                        <td>
                                            <p><?php echo e($passenger->first_name); ?></p>
                                        </td>
                                        <td>
                                            <p><?php echo e($passenger->middle_name); ?></p>
                                        </td>
                                        <td>
                                            <p><?php echo e($passenger->last_name); ?></p>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                            
                            <?php if(!empty($booking->flights[0]->air_line_name)): ?>

                                <?php

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
                                ?>

                                <h6><strong>Flight </strong> Details</h6>

                                <table class="table table-bordered">

                                    <tr class="bg-grey header">
                                        <td class="<?php echo e($airline_class); ?>">
                                            <h6>Airline Locator</h6>
                                        </td>
                                        <td class="<?php echo e($ticket_no_class); ?>">
                                            <h6>Ticket Number</h6>
                                        </td>
                                        <td>
                                            <h6>AirLine</h6>
                                        </td>

                                    </tr>

                                    <tr>

                                        <td class='<?php echo e($airline_class); ?>'>
                                            <p><?php echo e($airline_loc); ?></p>
                                        </td>
                                        <td class='<?php echo e($ticket_no_class); ?>'>
                                            <p> <?php echo e($ticket_no); ?> </p>
                                        </td>
                                        <td>
                                            <p><?php echo e($f_airline_name); ?></p>
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
                                    <?php $__currentLoopData = $booking->flights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <p><?php echo e($flight->flight_number); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e($flight->departure_airport); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e(date('d M Y', strtotime($flight->departure_date)) . ' - ' . date('H:i', strtotime($flight->departure_time))); ?>

                                                </p>
                                            </td>
                                            <td>
                                                <p><?php echo e($flight->arrival_airport); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e(date('d M Y', strtotime($flight->arrival_date)) . ' - ' . date('H:i', strtotime($flight->arrival_time))); ?>

                                                </p>
                                            </td>
                                            <td>
                                                <p><?php echo e($flight->booking_class); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e($flight->number_of_baggage); ?></p>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            <?php endif; ?>
                            <!-- <div class="before"> </div> -->
                            <?php if(!empty($booking->hotels[0]['hotel_name'])): ?>

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
                                    </tr>
                                    <?php $__currentLoopData = $booking->hotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <p><?php echo e($hotel->hotel_name); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e(date('d M Y', strtotime($hotel->check_in_date))); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e(date('d M Y', strtotime($hotel->check_out_date))); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e($hotel->total_nights); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e($hotel->meal_type); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e($hotel->room_type); ?></p>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                                <!-- <div class="before"> </div> -->
                            <?php endif; ?>

                            <?php if(!empty($booking->transports[0]['transport_type'])): ?>

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
                                    </tr>
                                    <?php $__currentLoopData = $booking->transports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <p><?php echo e($transport->transport_type); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e($transport->airport); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e($transport->location); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e(date("d M Y", strtotime($transport->transport_date))." ".date('H:i', strtotime($transport->time))); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e($transport->car_type); ?></p>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            <?php endif; ?>

                            <?php if(!empty($booking->visas[0]['visa_category'])): ?>

                                <h6><strong>Visa</strong> Detail</h6>
                                <table class="table table-bordered">

                                    <tr class="bg-grey header">
                                        <td>
                                            <h6>Visa Category</h6>
                                        </td>
                                        <td>
                                            <h6>Visa Country</h6>
                                        </td>
                                    </tr>
                                    <?php $__currentLoopData = $booking->visas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <p><?php echo e($visa->visa_category); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e($visa->visa_country); ?></p>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            <?php endif; ?>

                            <h6><strong>Charges</strong> Detail</h6>
                            <table class="table table-bordered">

                                <tr class="bg-grey header">
                                    <td>
                                        <h6>Quanity & fare</h6>
                                    </td>
                                    <td>
                                        <h6>CC Charges</h6>
                                    </td>
                                    <td>
                                        <h6>Total</h6>
                                    </td>
                                </tr>

                                <?php $__currentLoopData = $booking->prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($price->total > 0): ?>
                                    <tr>
                                        <td>
                                            <p><?php echo e($price->booking_type); ?> ( <?php echo e($price->quantity); ?> * <?php echo e($price->sale_cost); ?> )</p>
                                        </td>
                                        <td>
                                            <p></p>
                                        </td>
                                        <td>
                                            <p><?php echo e($booking->currency); ?> <?php echo e((!empty($price->total)) ? $price->total : 0); ?></p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $otherChargesSum = 0;
                                ?>
                                <?php if(count($booking->otherCharges) > 0): ?>
                                    <tr class="bg-grey header">
                                        <td>
                                            <h6>Other Charges # </h6>
                                        </td>
                                        <td>
                                            <h6>Comments</h6>
                                        </td>
                                        <td>
                                            <h6>Amount</h6>
                                        </td>
                                    </tr>
                                    <?php $__currentLoopData = $booking->otherCharges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oc_key => $otherCharge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    if ($otherCharge->charges_type !== 'CC Charges') {
                                    }
                                    $otherChargesSum = $otherChargesSum + $otherCharge->amount;
                                    ?>
                                    
                                        <tr>
                                            <td>
                                                <p><?php echo e($oc_key+1); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e($otherCharge->comments); ?></p>
                                            </td>
                                            <td>
                                                <p><?php echo e($booking->currency . ' ' . $otherCharge->amount); ?></p>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <tr style="border-top: 2px solid;">
                                    <td> </td>
                                    <td style="text-align: right;">
                                        <p><b>Total Invoice Amount</b></p>
                                    </td>
                                    <td>
                                        <p> <?php echo e($booking->currency . ' ' . $booking->total_sales_cost+$otherChargesSum); ?> </p>
                                    </td>
                                </tr>

                            </table>
                            <!-- <div class="pagebreak"> </div> -->

                            <h6><strong>Payment</strong> Detail</h6>

                            <table class="table table-bordered">

                                <tr class="bg-grey header">
                                    <td style="width:20%">
                                        <h6>Installment # </h6>
                                    </td>
                                    <td style="width:20%">
                                        <h6>Transactions</h6>
                                    </td>
                                    <td style="width:20%">
                                        <h6>Transactions Date</h6>
                                    </td>
                                    <td style="width:20%">
                                        <h6>Balance</h6>
                                    </td>
                                    <td style="width:20%">
                                        <h6>Due Date</h6>
                                    </td>
                                </tr>
                                <?php
                                $total_invoice_amount = $booking->total_sales_cost+$otherChargesSum;
                                ?>
                                
                                <?php $__currentLoopData = $booking->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                
                                
                                <?php

                                    $total_with_other_charges = $payment->remaining_amount+$otherChargesSum;
                                ?>
                                    <tr>
                                        <td>
                                            <p>
                                                
                                                <?php if($payment->installment_no == 0 && $payment->other_charges_id == null): ?>
                                                    <?php echo e('Initial Payment'); ?>

                                                <?php elseif($payment->other_charges_id != null): ?>
                                                    <?php echo e($payment->otherCharges->charges_type); ?>

                                                <?php else: ?>
                                                    <?php echo e($payment->installment_no); ?>

                                                <?php endif; ?>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                            <?php echo e($booking->currency . ' ' . $payment->reciving_amount); ?>

                                            <?php if($payment->cc_charges > 0): ?>
                                                <?php echo e(" (+ ".$payment->cc_charges ." CC)"); ?>

                                            <?php endif; ?>
                                            </p>
                                        </td>
                                        <td>
                                            <p><?php echo e(($payment->deposit_date != null) ? date('d M Y', strtotime($payment->deposit_date)) : '-'); ?></p>
                                        </td>
                                        <td>
                                            <?php
                                                $amount_without_cc = $payment->reciving_amount+$payment->cc_charges;
                                            ?>
                                            
                                            
                                            
                                            <p><?php echo e($booking->currency . ' ' . $total_invoice_amount - $amount_without_cc); ?></p>
                                            

                                            
                                            <?php
                                                // $total_invoice_amount = $total_invoice_amount - $payment->reciving_amount;
                                                $total_invoice_amount = $total_invoice_amount - $amount_without_cc;
                                            ?>
                                        </td>
                                        <td>
                                            <p class="d-none"> <?php echo e($payment->remaining_amount == 0 ? 'Payment Completed' : ''); ?> 
                                            </p>
                                            <p> <?php echo e($total_invoice_amount == 0 ? 'Payment Completed' : ''); ?> 
                                            </p>
                                        </td>
                                    </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </table>

                            
                            <?php if($booking->refunds->where('refund_status', 1)->count() > 0): ?>
                                <h6><strong>Refund</strong> Breakdown</h6>

                                <table class="table table-bordered">

                                    <tr class="bg-grey header">
                                        <td style="width:20%">
                                            <h6>Date</h6>
                                        </td>
                                        <td style="width:20%">
                                            <h6>Refund Type</h6>
                                        </td>
                                        <td style="width:20%">
                                            <h6>Total Received</h6>
                                        </td>
                                        <td style="width:20%">
                                            <h6>Admin Charges</h6>
                                        </td>
                                        <td style="width:20%">
                                            <h6>Refunded Amount</h6>
                                        </td>
                                    </tr>
                                    <?php $__currentLoopData = $booking->refunds->where('refund_status', 1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        
                                        <tr>
                                            <td>
                                                <p><?php echo e(date('d M Y', strtotime($refund->refund_requeseted_on))); ?></p>
                                            </td>
                                            <td>
                                                <p> <?php echo e(str_replace("booking", "", $refund->refund_type).' Refund'); ?></p>
                                            </td>
                                            <td>
                                                <p> <?php echo e($refund->paid_amount); ?> </p>
                                            </td>
                                            <td>
                                                <p> <?php echo e($refund->service_charges); ?> </p>
                                            </td>
                                            <td>
                                                <p><?php echo e($refund->refunded_amount); ?></p>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                                <hr>
                            <?php endif; ?>



                            
                            <h6>Your Total <strong>Outstanding</strong> Balanace Is: <span class='text-danger'><b><?php echo e($booking->currency .$booking->balance_amount+$booking->otherCharges->sum('remaining_amount')); ?></b></span>. Your cooperation in ensuring early payment is highly appreciated.</h6>

                            <table class="table table-bordered">

                                <tr class="bg-grey header">
                                    <td style="width:20%">
                                        <h6>Outstanding Type </h6>
                                    </td>
                                    <td style="width:20%">
                                        <h6>Transactions</h6>
                                    </td>
                                    <td style="width:20%">
                                        <h6>Transactions Date</h6>
                                    </td>
                                    <td style="width:20%">
                                        <h6>Balance Amount</h6>
                                    </td>
                                    <td style="width:20%">
                                        <h6>Due Date</h6>
                                    </td>
                                </tr>
                                <?php if(count($booking->installmentPlan) > 0): ?>

                                <?php
                                $total_balance = 0;
                                ?>
                                <?php $__currentLoopData = $booking->installmentPlan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <?php if($installment->is_received == 0 && $installment->amount > 0): ?>
                                        <?php
                                        $total_balance = $total_balance+$installment->amount
                                        ?>
                                        <tr>
                                            <td>
                                                <p><span class='text-danger'> <?php echo e("Installment # ".$installment->installment_number); ?> </span></p>
                                            </td>
                                            <td>
                                                <p> <?php echo $installment->is_received == 0 ? "<span class='text-danger'>" . $booking->currency . ' 0 </span>' : $booking->currency . ' ' . $installment->amount; ?>

                                                </p>
                                            </td>
                                            <td>
                                                <p><?php echo $installment->is_received == 0 ? "<span class='text-danger'>Pending</span>" : date('d M Y', strtotime($installment->received_on)); ?>

                                                </p>
                                            </td>
                                            <td>
                                                <p><?php echo $installment->is_received == 0 ? "<span class='text-danger'>" . $booking->currency . ' ' . $installment->amount . '</span>' : $booking->currency . ' ' . ' 0'; ?>

                                                </p>
                                            </td>
                                            <td>
                                                
                                                <p> <?php echo e(date('d M Y', strtotime($installment['due_date']))); ?> </p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <?php $__currentLoopData = $booking->otherCharges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $otherCharges): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($otherCharges->remaining_amount > 0): ?>
                                        <?php
                                        $total_balance = $total_balance + $otherCharges->remaining_amount;
                                        ?>
                                        <tr>
                                            <td>
                                                <p><span class='text-danger'> <?php echo e($otherCharges->charges_type); ?> </span></p>
                                            </td>
                                            <td>
                                                <p> <span class='text-danger'><?php echo e($booking->currency .$otherCharges->reciving_amount); ?></span> </p>
                                            </td>
                                            <td>
                                                <p><span class='text-danger'>Pending</span>
                                                </p>
                                            </td>
                                            <td>
                                                <p><?php echo $otherCharges->remaining_amount > 0 ? "<span class='text-danger'>" . $booking->currency . ' ' . $otherCharges->remaining_amount . '</span>' : $booking->currency . ' ' . ' 0'; ?>

                                                </p>
                                            </td>
                                            <td>
                                                <p> - </p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td colspan="3"><p><span class='text-danger'><b>Total Balance</b></span></p></td>
                                    <td><p><span class='text-danger'><b><?php echo e($booking->currency .$total_balance); ?></b></span></p></td>
                                    <td></td>
                                </tr>
                                 <?php else: ?>
                                 <tr>
                                    <td colspan="3"><p><span class='text-danger'><b>Total Balance</b></span></p></td>
                                    <td><p><span class='text-danger'><b><?php echo e($booking->currency .$booking->balance_amount); ?></b></span></p></td>
                                    <td></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                            <hr>

                            <h6 class="text-primary"><strong>** Please</strong> be advised that all payments should be deposited exclusively in the following banks. <strong>**</strong></h6>

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
                            <h6><strong>Note: </strong> Payments made to any bank other than those listed above will not be accepted.</h6>
                        </td>
                    </tr>
                    <?php if(!empty($booking->comments)): ?>
                        <tr>
                            <td colspan="7">
                                <h6>Booking Notes</h6>
                                <p><?php echo e($booking->comments); ?></p>
                            </td>
                        </tr>
                    <?php endif; ?>

                </table>

                <div style="break-after: page;"></div>
                <table class="table">
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul style="columns: 2;">
                                        <li>First & Business class passengers are requested to check in at least 2 hrs
                                            before departure. </li>
                                        <li>Economy class passengers are requested to check in at least 3 hrs.
                                            <br> Before your departure time at airport.</li>
                                        <li>Date Changes before departure and before inbound departure is not
                                            permitted</li>
                                        <li>Cancellation fees before /after departure or no shows or partly used
                                            tickets are non refundable</li>
                                        <li>Deposits are non-refundable and rights are non-changeable, non refundable
                                            from the point of confirmation</li>
                                        <li>We take deposits that secure your seats however; fares may change until
                                            the balance payment made or until issuance.</li>
                                        <li>Please pay balances when due because failure to do so may lead to the
                                            cancellation of your holiday/flights.</li>
                                        <li>And still leave you liable to pay cancellation charges.</li>
                                        <li>All credit/debit card payments are subject to a surcharge</li>
                                        <li>Booking has been placed with our consolidator</li>
                                        <li>This document is the confirmation of booking only</li>
                                        <li>Debit/credit card payment will be charged by SEVEN ZONES SERVICES LTD.
                                        </li>
                                    </ul>
                                    <br>
                                    <ul>
                                        <li>Seven Zones would process an application in adherence to the client's
                                            unique preference(s) to our travel advisor(s) or as given on our website,
                                            and hence we will not be responsible for any incorrect information
                                            provided to us, through any medium. Although Seven Zones makes every best
                                            possible effort to obtain all the relevant and correct information within
                                            a specific period of time, however we deny to accept any responsibility,
                                            due to the following occurrences : </li>
                                        <ol>
                                            <li> (a) Refusal of Visa Application</li>
                                            <li> (b) Denial of Visa</li>
                                            <li> (c) Visa Issued with Incorrect passenger details</li>
                                            <li> (d) Delay in processing times of the Visa Application</li>
                                        </ol>
                                    </ul>
                                    <h6 class="border_bottom"><b>Terms and Conditions</b></h6>

                                    <?php if($booking->company_id != 6): ?>
                                        <ul>
                                            <li>Please note by replying "yes" or "All details are correct" or " I
                                                Agree ok
                                                " and any other confirmation to this email means you agree to our
                                                terms
                                                and conditions and the details or your booking including names, flight
                                                details and other package details are correct.</li>
                                            <li>All ticket(s) booked through us are strictly non-changeable and
                                                non-refundable.</li>
                                            <li>We take deposits that secure your seats however; fares may change
                                                until
                                                the balance payment made or until issuance.</li>
                                            <li>All upfront deposits are completely Non-Refundable.</li>
                                            <li>Please ensure that you must reply the email. Failure to reply the
                                                email
                                                would be considered as confirmation that all the details given on
                                                invoice
                                                regarding flight details and passenger details are correct.
                                                Furthermore
                                                company will not take any responsibility if there is any mistake if
                                                you
                                                fail to reply the email.</li>
                                            <li>Please note that it is the passenger's responsibility to ensure that
                                                the
                                                outstanding balance payment is paid within the Due date, wherein
                                                failure
                                                to do so would result in the cancellation of your booking and
                                                forfeiting
                                                of the upfront deposit money paid to us. Please ensure to update us
                                                about
                                                any payments that you make.</li>
                                            <li>Cancellations / Refunds are subject to the guidelines / restrictions
                                                imposed by the airline company less our administration charge. Please
                                                note
                                                that the refunds are processed in 7-8 business weeks, and no refunds
                                                would
                                                be paid out, till the same have been received from the relevant
                                                airline /
                                                supplier.</li>
                                            <li>All changes / amendments are subject to fare restrictions /
                                                availability
                                                of seats at the time of making an amendment.</li>
                                            <li>All quotes are subject to availability and are not guaranteed, until
                                                the
                                                ticket(s) have been issued, irrespective of the fact that the full
                                                payment
                                                has been made, as airline fares and seat availability changes on an
                                                ongoing basis.</li>
                                            <li>As airline carriers prefer e-ticket as the most preferred mode of
                                                travel,
                                                hence no paper ticket(s) will be issued.</li>
                                            <li>Passenger(s) are requested to re-confirm their booking(s) with either
                                                the
                                                travel agency 72 hrs prior to the flight departure time to obtain
                                                information about last minute changes, irrespective of the guidelines
                                                of
                                                the airline company.</li>
                                            <li>Passenger(s) are advised to check-in at least 3 hours prior to the
                                                flight
                                                departure time (for International Flights) and 2 hours prior to the
                                                flight
                                                departure time (for Domestic Flights).</li>
                                            <li>Please note that it is your responsibility to ensure that all your
                                                travel
                                                documents are correct and valid for travel. This includes your
                                                e-ticket,
                                                passport, visa (if required), travel insurance (if required) and any
                                                additional travel document(s).</li>
                                            <li>We accept no liability in the event wherein you are denied boarding or
                                                miss the flight or refused an entry into any country due to failure on
                                                your part to carry the correct passport, visa or other documents
                                                required
                                                by any airline, authority or country.</li>
                                            <li>Travellers must ensure that their passport is valid for at least 6
                                                months,
                                                from their return date of travel.</li>
                                            <li>Passenger(s) travelling to The United States of America would need to
                                                apply for an ESTA Visa by visiting the U.S. Government website at: <a
                                                    href="https://esta.cbp.dhs.gov/esta/"
                                                    target="__new">https://esta.cbp.dhs.gov/esta/</a> .</li>
                                            <li>It is recommended that all the passenger(s) should be covered by a
                                                travel
                                                insurance policy to ensure protection against unforeseen situations.
                                                Please note that the travel insurance is not a part of your booking
                                                and
                                                hence needs to be purchased as a separate component. Please note that
                                                any
                                                claim made under the insurance policy would be governed by the
                                                guidelines
                                                of the insurance company and we would not be liable to accommodate any
                                                claims / request(s) from passenger(s) regarding the same.</li>
                                            <li>All transactions carried out on a credit card would attract a
                                                surcharge of
                                                3% over and above the amount (which in case of a refund would be
                                                non-refundable), however transactions carried out on a debit card,
                                                would
                                                be free from any surcharge. It may also be noted that documents such
                                                as
                                                copies of passport(s) / card(s) through which the payment was made
                                                could
                                                be requested, against unsuccessful / unauthorized payments, in order
                                                to
                                                verify the genuinity of the cardholder.</li>

                                            <li>All the authorized payments taken by Seven Zones which is the parent
                                                company of HajjUmrah4U.</li>


                                        </ul>
                                    <?php else: ?>
                                        <ul>
                                            Hajj & Umrah Terms and Conditions

                                            <li>We at Seven Zones Services ltd T/A Hajjumrah4u.com, are committed to
                                                provide you with a comprehensive and hassle-free Umrah experience. Our
                                                policy is designed to cover all aspects of your pilgrimage journey,
                                                ensuring that you can focus on the spiritual aspects of the journey
                                                without having to worry about any logistical or administrative
                                                details.
                                            </li>

                                            <li>Below are the key aspects of our policy that we would like to
                                                highlight:
                                            </li>

                                            <li>Visa and Documentation: Our team will assist you with obtaining the
                                                necessary visas and other travel documents required for your journey.
                                                We
                                                will also provide you with guidance on the documentation process and
                                                ensure that all paperwork is submitted in a timely manner.</li>

                                            <li>Please note we are not the embassy who approve your visa and only
                                                submit
                                                your visa application on your behalf. Company is not responsible for
                                                any
                                                loss due to rejection of visa or delay from embassy.</li>

                                            <li>Travel and Accommodation: We will arrange your travel and
                                                accommodation,
                                                including flights, ground transportation, and hotel reservations. Our
                                                team
                                                will ensure that you are comfortably accommodated during your stay in
                                                Saudi Arabia, with access to all necessary facilities and services.
                                            </li>

                                            <li>Ziyarat and Guided Tours: Our package includes guided tours of
                                                important
                                                religious and historical sites, including visits to the Prophet's
                                                Mosque
                                                and other sacred locations. We will ensure that you have a
                                                knowledgeable
                                                guide to help you navigate these sites and provide you with
                                                information on
                                                their significance.</li>

                                            <li>Medical and Safety: We prioritize your health and safety and take
                                                necessary measures to ensure that your trip is comfortable and secure.
                                                We
                                                provide medical facilities and assistance in case of any emergencies,
                                                as
                                                well as insurance coverage to protect you from any unforeseen
                                                circumstances.</li>

                                            <li>Customer Support: Our team will be available to assist you with any
                                                queries or concerns before, during, and after your journey. We strive
                                                to
                                                provide excellent customer service and will be at your service
                                                throughout
                                                your trip.</li>

                                            <li>We hope that our policy provides you with the assurance and confidence
                                                you
                                                need to embark on your Umrah journey with us. We look forward to
                                                serving
                                                you and ensuring that your pilgrimage is a memorable and fulfilling
                                                experience.</li>

                                            <li><strong>Following are our Terms & Conditions:</strong></li>

                                            <li><strong>BOOKING AND PAYMENT</strong></li>
                                            <li>To book an Umrah package with our agency, customers must provide their
                                                full name, date of birth, passport details, and payment of a deposit.
                                                The
                                                deposit is non-refundable and confirms the booking.</li>

                                            <li>The remaining balance must be paid before the departure date, as
                                                specified
                                                by our agency. Failure to pay the balance may result in cancellation
                                                of
                                                the booking and forfeiture of the deposit.</li>

                                            <li>Payments can be made by bank transfer, credit/debit card or cash.</li>

                                            <li><strong>CANCELLATION AND REFUND POLICY</strong></li>
                                            <li>If a customer wishes to cancel their booking, they must provide
                                                written
                                                notice to our agency. The cancellation charges will depend on the date
                                                of
                                                cancellation and will be specified in the booking confirmation.</li>

                                            <li>Refunds will be processed within 3-6 weeks of the cancellation, less
                                                any
                                                applicable charges.</li>

                                            <li>Our agency reserves the right to cancel a booking at any time due to
                                                circumstances beyond our control, such as political unrest, natural
                                                disasters or changes in visa regulations. In such cases customers will recieve refund as per airline and supplier's cancellation policy less company's administration fees.</li>

                                            <li> Please ensure that you must reply the email. Failure to reply the email would be considered as confirmation that all the details given on invoice regarding flight details and passenger details are correct. Furthermore company will not take any responsibility if there is any mistake if you fail to reply the email.</li>

                                            <li><strong>FLIGHT OR HOTEL ITINERARY CHANGES</strong></li>
                                            <li>Our agency reserves the right to change the itinerary due to
                                                circumstances
                                                beyond our control, such as changes in airline schedules, hotel
                                                availability, overbooking by hotel or other factors. Customers will be
                                                notified of any changes as soon as possible.</li>

                                            <li>If a customer wishes to change their itinerary after booking, they
                                                must
                                                provide written notice to our agency. Changes may be subject to
                                                additional
                                                charges.</li>

                                            <li><strong>TRAVEL DOCUMENTS AND HEALTH REQUIREMENTS</strong></li>
                                            <li>Customers are responsible for obtaining all necessary travel
                                                documents,
                                                such as passports, visas and health certificates, and ensuring that
                                                they
                                                meet all the requirements of the Saudi authorities for Hajj and Umrah
                                                travel.</li>

                                            <li>Our agency can assist with obtaining visas for customers, but cannot
                                                guarantee approval or issuance of visas. Customers may be required to
                                                provide additional documentation or attend an interview at the Saudi
                                                embassy or consulate.</li>

                                            <li>Customers must also ensure that they are medically fit to travel for
                                                Umrah, and should consult their doctor before booking.</li>

                                            <li><strong>TRAVEL INSURANCE<strong></li>
                                            <li>Our agency strongly recommends that customers purchase travel
                                                insurance to
                                                cover any unforeseen circumstances that may arise during their trip,
                                                such
                                                as medical emergencies, cancellation or curtailment, loss of baggage
                                                or
                                                personal belongings, and personal liability.</li>

                                            <li><strong>LIABILITY AND DISCLAIMERS</strong></li>
                                            <li>Our agency will not be liable for any loss, damage or injury to
                                                customers,
                                                their property or third parties during their travel for Hajj or Umrah.
                                            </li>

                                            <li>Our agency will not be liable for any delays or cancellations due to
                                                circumstances beyond our control, such as airline strikes, natural
                                                disasters, political unrest or other factors.</li>

                                            <li>Customers are responsible for their own safety and wellbeing during
                                                their
                                                travel for Hajj or Umrah, and should comply with all local laws,
                                                customs
                                                and regulations.</li>

                                            <li>Our agency reserves the right to amend these terms and conditions at
                                                any
                                                time, and customers will be notified of any changes.</li>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

                <div class="divFooter row" style="margin: 0 auto;width: 100%;">
                    <table class="table">
                        <tr>
                            <td><img src="<?php echo e(asset('images/atol.png')); ?>" width="100"></td>
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
                            <td><img src="<?php echo e(asset('images/iata-logo-new.png')); ?>" width="100"></td>
                        </tr>
                    </table>

                </div>

              </div>

          </div>
          
      </div>
  </body>

  </html>
<?php /**PATH /home/ashraf/Documents/web_dev/mis/resources/views/modules/CRM/booking/invoices/invoice.blade.php ENDPATH**/ ?>