  <!doctype html>
  <html class="no-js " lang="en">

  <head>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
      <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
      <!-- PRINT Css-->
        <link href="<?php echo e(URL::asset('build/css/bootstrap/bootstrap-print.css')); ?>"  rel="stylesheet" media="print"/>
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

              .row {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
                margin-right: -15px;
                margin-left: -15px;
            }

              .col-lg-2 {
                  -webkit-box-flex: 0;
                  -ms-flex: 0 0 16.666667%;
                  flex: 0 0 16.666667%;
                  max-width: 16.666667%;
              }

              .col-lg-3 {
                  -webkit-box-flex: 0;
                  -ms-flex: 0 0 25%;
                  flex: 0 0 25%;
                  max-width: 25%;
              }
              .col-lg-12 {
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 100%;
                    flex: 0 0 100%;
                    max-width: 100%;
                }

                .col-lg-10 {
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 83.333333%;
                    flex: 0 0 83.333333%;
                    max-width: 83.333333%;
                }

              .text-left {
                  text-align: left !important;
              }

              .passenger .table tr td {
                  background-color: #0F70B7!important;
              }
              .text-white {
                    color: #fff !important;
                }
                .qrCode{
                    /* background-color: #ffffff !important; */
                    position: relative !important;
                    /* top: -30px !important; */
                    text-align: center !important;
                    /* border-radius: 7px !important; */
                    /* padding: 10px !important; */
                }

          }
      </style>
  </head>

  <body class="theme-blush"> 

      <div class="container-fluid">
          <div class="row p-2" style="">
              <div class="col-lg-12">
                  <table class="table no-border">
                      <tbody>
                          <tr class="">
                              <td style="border-top:0px" width="">
                                  <img src="<?php echo e(asset('images/companyLogos/' . $booking->company->logo)); ?>" width="250">
                              </td>
                              <td style="border-top:0px">
                                <h2 class="text-center p-2 mt-4">E-TICKET</h2>
                                </td>
                              <td style="border-top:0px; text-align: center; vertical-align: middle;" class="text-right" width="" style="">
                                  <p class="text-right">
                                        <span class="" style="color:#F58634 !important"><strong>Customer Support:</strong></span>
                                        <span>02079932994</span>
                                        
                                  </p>
                                  <p class="text-right">
                                    <span class="" style="color:#F58634 !important"><strong>Email:</strong></span>
                                    <span style="text-transform: lowercase !important;"><?php echo e($booking->company->email); ?></span>
                                </p>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>

          <div class="row">
              <div class="col-lg-12">
                  <div class="card no-border" >
                      <div class="card-body text-white passenger">
                          <div class="row">
                              <div class="col-10 p-3" style="border-radius: 10px; background-color: #0F70B7;">
                                  <table class="table no-border">

                                    <tbody>
                                        <tr>
                                            <td class="no-border"><h5><strong>Passenger Name</strong></h5></td>
                                            <td class="no-border"><h5><strong>Ticket Number</strong></h5></td>
                                            <td class="no-border"><h5><strong>Airline Reference (PNR)</strong></h5></td>
                                        </tr>
                                        <?php $__currentLoopData = $booking->passengers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pKey => $passenger): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="no-border"><h5><?php echo e($passenger->last_name); ?>/<?php echo e($passenger->first_name); ?> <?php echo e($passenger->middle_name); ?></h5></td>
                                            <td class="no-border"><h5><?php echo e($passenger->ticket_number); ?></h5></td>
                                            <td class="no-border"><h5><?php echo e($passenger->pnr_code); ?></h5></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                  </table>
                              </div>
                              
                              <div class="col-lg-2 qrCode text-center"> 
                                <h4 class="" style="color:#F58634"><strong>Contact Us</strong></h4>
                                <img src="<?php echo e(asset('images/eTicket/e-ticket-qr.jpg')); ?>" width="200">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

          <?php $__currentLoopData = $booking->flights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fKey => $flight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="row clearfix mt-3">
                  <div class="col-lg-12">
                      <div class="card no-border flightCard" style="border-radius: 10px;">
                          <div class="card-header text-white" style="border-radius: 10px 10px 0px 0px; background-color: #F58634;">
                              <h4 class="card-title mb-0"><strong> Flight <?php echo e($fKey + 1); ?> - <?php echo e($flight->departure_airport); ?> to <?php echo e($flight->arrival_airport); ?></strong></h4>
                          </div>
                          <div class="card-body">
                              <div class="row p-2">
                                  <div class="col-lg-2">
                                      <h5>CARRIER</h5>
                                      <h5><strong><?php echo e($flight->air_line_name); ?></strong></h5>
                                      
                                  </div>
                                  <div class="col-lg-2">
                                      <h5>FLIGHT NO</h5>
                                      <h5><strong><?php echo e($flight->flight_number); ?></strong></h5>
                                      
                                  </div>
                                  <div class="col-lg-3">
                                      <h5>DEPARTURE</h5>
                                      <h5 class="mb-2"><strong><?php echo e($flight->departure_time); ?></strong></h5>
                                      <h5 class="mb-0 fw-medium text-truncate"><?php echo e($flight->departure_airport); ?></h5>
                                      <h5 class="mb-0"><?php echo e(date('d-M-Y', strtotime($flight->departure_date))); ?></h5>
                                  </div>
                                  <div class="col-lg-2 text-left">
                                      
                                      <div class="mt-3">

                                          <img src="<?php echo e(asset('images/eTicket/airplane.png')); ?>" width="40">
                                      </div>
                                  </div>
                                  <div class="col-lg-3">
                                      <h5>ARRIVAL</h5>
                                      <h5 class="mb-2"><strong><?php echo e($flight->arrival_time); ?></strong></h5>
                                      <h5 class="mb-0 fw-medium text-truncate"><?php echo e($flight->arrival_airport); ?></h5>
                                      <h5 class="mb-0"><?php echo e(date('d-M-Y', strtotime($flight->arrival_date))); ?></h5>
                                  </div>
                              </div>
                          </div>
                          <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-4">
                                          <h5>Ticket Status</h5>
                                          <h5 class="mb-0"><strong>Confirmed</strong></h5>
                                        </div>
                                        <div class="col-2">
                                          <h5>BAG</h5>
                                          <h5 class="mb-0"><strong><?php echo e($flight->number_of_baggage); ?></strong></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 text-right d-none">
                                    <?php if($loop->last): ?>
                                        <!-- Add your images here, since this is the last iteration -->
                                        <div class="row">
                                            <div class="col-3">
                                                <img src="<?php echo e(asset('images/eTicket/bags.png')); ?>" width="100">
                                            </div>
                                            <div class="col-3">
                                                <img src="<?php echo e(asset('images/eTicket/car-rental.png')); ?>" width="100">
                                            </div>
                                            <div class="col-3">
                                                <img src="<?php echo e(asset('images/eTicket/map-pin.png')); ?>" width="100">
                                            </div>
                                            <div class="col-3">
                                                <img src="<?php echo e(asset('images/eTicket/customer-support.png')); ?>" width="100">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                          </div>
                      </div>
                  </div>
              </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <div class="row clearfix mt-3">
            <div class="col-12 text-center">
                <img class="rounded material-shadow" width="100%" src="<?php echo e(asset('images/eTicket/banner.png')); ?>" data-holder-rendered="true">
            </div>
          </div>
          <div class="row clearfix mt-3">

              <div class="col-lg-12">
                  
                  <table class="table">
                      <tr>
                          <td>
                              <div class="row">
                                  <div class="col-lg-12">
                                      <h3 class="border_bottom mb-2"><b>Terms and Conditions & Important Travel
                                              Information</b></h3>
                                      <p><strong>To ensure a smooth and enjoyable journey, please note the following essential details:</strong></p>
                                      <ul style="font-size: 14px;">
                                          <li>Please report to the check-in desk at least <b>3 hours</b> prior to your
                                              scheduled departure time.
                                              <br>Additionally, we recommend reconfirming your
                                              outbound and inbound flights directly with the airline at least <b>72
                                                  hours before departure</b>. Alternatively, you can contact our
                                              dedicated customer care team on <b>02079932994</b> for assistance.</li>
                                          <li>Please be aware that tickets must be used in the <b>sequence outlined</b>
                                              in your itinerary</li>
                                          <li>In the event of <b>overbooking</b>, we advise <b>checking in online</b>
                                              and <b>selecting your seat</b> in advance to ensure a smooth journey and
                                              secure your preferred seat.</li>
                                          <li>Please note that the Electronic Ticket record may not reflect any
                                              <b>changes</b>, <b>schedule updates</b>, or <b>cancellations</b> made
                                              after the electronic ticket was issued. For the most <b>up-to-date</b> and
                                              accurate flight information, please contact the airline directly at least
                                              <b>48 hours</b> prior to your flight or contact our customer care team for
                                              assistance.
                                          </li>

                                          <li>For flights with a <b>piece-based baggage allowance</b> (PC), each
                                              individual piece of baggage <b>must not exceed 23kg</b>. Infants
                                              travelling with an accompanying adult are entitled to a maximum baggage
                                              allowance of 10kg.</li>
                                          <li>A copy of the <b>terms and conditions</b> of travel, as well as the
                                              Carrier Liability Notice, can be obtained from our travel agency or the
                                              transporting carrier upon request.</li>
                                          <li>All departure and arrival times are displayed in <b>local time</b>.</li>
                                      </ul>
                                      <p><strong>We hope this information helps ensure a pleasant and hassle-free travel
                                              experience. If you have any further questions or concerns, please do not
                                              hesitate to contact us.</strong></p>
                                  </div>
                              </div>
                          </td>
                      </tr>
                  </table>

                  <div class="divFooter row d-none" style="margin: 0 auto;width: 100%; ">
                      <table class="table">
                          <tr>
                              <td><img src="<?php echo e(asset('images/atol.png')); ?>" width="100"></td>
                              <td>
                                  <div class="text-center mt-4">
                                      <p class="mb-0"><b>Seven Zones Travel: 9 Station Road, West Drayton, UB7 7BT</b>
                                      </p>
                                      <p class="mb-0"><b>Tel: 02079932994; Email: sales@sevenzones.co.uk; URL: www.sevenzones.co.uk</b></p>
                                      <p class="mb-0">Seven Zones Services Limited England & Wales. Company Registration No. 09784212</p>
                                      <p class="mb-0"><b>Agents for ATOL and IATA holders</b></p>
                                  </div>
                              </td>
                              <td class="text-right"><img src="<?php echo e(asset('images/iata-logo-new.png')); ?>" width="100"></td>
                          </tr>
                      </table>
                  </div>
              </div>
          </div>
          
      </div>
      <script>
        window.onload = function () {
            let flightCards = document.querySelectorAll('.flightCard');

            flightCards.forEach((card, index) => {
                // Get the height of the card and check if it exceeds a threshold (for example, 1000px)
                let cardHeight = card.offsetHeight;

                // Threshold value (you may adjust this as per your needs)
                let thresholdHeight = 1000;

                if (cardHeight > thresholdHeight) {
                    // If the flight card exceeds the threshold height, add a page break before the card
                    let pageBreak = document.createElement('div');
                    pageBreak.style.pageBreakBefore = 'always';
                    card.parentNode.insertBefore(pageBreak, card);
                }
            });
        };

      </script>
      <style>
        @media print {
            .flightCard {
                page-break-inside: avoid;
            }
        }

      </style>
  </body>

  </html>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/invoices/e-ticket.blade.php ENDPATH**/ ?>