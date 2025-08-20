<div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
    <h5 class="modal-title" id="fullscreeexampleModalLabel">Email Template | Booking Number:
        <?php echo e($booking->booking_prefix . $booking->booking_number); ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body" id="emailTemplateContent">

    <div class="row">

        <div class="col-lg-12">

            <h6>Dear Valued Passenger,<br>Greetings from <?php echo e($booking->company->name); ?></h6>

            <p>Please check your invoice, flight details and name spellings (Compulsory: surname and first name as per
                passport). If there are any mistakes please do let us know by email so that we can make it correct. In
                case if everything is correct and you agree with that then reply back with "OK GO AHEAD" so that we can
                proceed with your booking for further steps.</p>
<p> Please note by replying "yes" or "All details are correct" or " I Agree ok " and any other confirmation to this email means you agree to our terms and conditions and the details or your booking including names, flight details and other package details are correct.</p>
        </div>

    </div>
    <hr>
    <div class="row">

        <div class="col-lg-6">

            <h6 class="text-primary"><?php echo e($booking->company->name); ?></h6>

            <p class="mb-0"><?php echo e($booking->company->address); ?></p>

            <p class="mb-0"><strong>Complain Line:</strong> <?php echo e($booking->company->phone); ?></p>

            <p class="mb-0"><strong>Email:</strong> <?php echo e($booking->company->email); ?></p>

        </div>

        <div class="col-lg-6">

            <h6 class="text-primary">Booking Confirmation Invoice</h6>

            <p class="mb-0"><strong>Invoice Date:</strong> <?php echo e(date('d-m-Y', strtotime($booking->booking_date))); ?></p>

            <p class="mb-0"><strong>Invoice No:</strong> <?php echo e($booking->booking_prefix . $booking->booking_number); ?></p>

        </div>

    </div>
    <hr>

    <div class="row mb-3">

        <div class="col-lg-12 border border-dark">

            <div class="table-responsive">

                <table class="table table-borderless table-sm">

                    <thead>

                        <tr>

                            <th colspan="3" class="mb-0 pb-0">

                                <h6 class="mb-0">To,</h6>

                            </th>

                        </tr>

                        <tr class="border-bottom border-dark">

                            <th colspan="3" class="mb-0 pb-0">

                                <h6 class="mb-0">
                                    <?php echo e($booking->passengers[0]->title . ' ' . $booking->passengers[0]->first_name . ' ' . $booking->passengers[0]->middle_name . ' ' . $booking->passengers[0]->last_name); ?>

                                </h6>

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <th class="mb-0 pb-0">Booking Confirmation No (PNR)</th>

                            <td class="mb-0 pb-0">:</td>

                            <td class="mb-0 pb-0"><?php echo $booking->flight_pnr; ?></td>

                        </tr>

                        <tr>

                            <th>Agent Name</th>

                            <td>:</td>

                            <td><?php echo e($booking->user->name); ?></td>

                        </tr>



                    </tbody>

                </table>

            </div>



        </div>

    </div>

    <div class="row mb-3">

        <h6>Flight Details</h6>

        <div class="col-lg-12 border border-dark">

            <?php echo $booking->flight_pnr; ?>


        </div>

        <?php if(count($booking->hotels) > 0): ?>

            <div class="col-lg-12 mb-3">

                <h6>Hotel Details</h6>
                <table class="table table-bordered table-sm">
                    <tr class="header">

                        <td>

                            <h6 class="mb-0 pb-0">Hotel Name</h6>

                        </td>

                        <td>

                            <h6 class="mb-0 pb-0">Check In</h6>

                        </td>

                        <td>

                            <h6 class="mb-0 pb-0">Check Out</h6>

                        </td>

                        <td>

                            <h6 class="mb-0 pb-0">Nights</h6>

                        </td>

                        <td>

                            <h6 class="mb-0 pb-0">Meal Type</h6>

                        </td>

                        <td>

                            <h6 class="mb-0 pb-0">Room</h6>

                        </td>

                    </tr>

                    <?php $__currentLoopData = $booking->hotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e($hotel['hotel_name']); ?></p>
                            </td>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e(date('d M Y', strtotime($hotel['check_in_date']))); ?></p>
                            </td>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e(date('d M Y', strtotime($hotel['check_out_date']))); ?></p>
                            </td>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e($hotel['total_nights']); ?></p>
                            </td>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e($hotel['meal_type']); ?></p>
                            </td>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e($hotel['room_type']); ?></p>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </table>

                <!-- <div class="before"> </div> -->

            </div>

        <?php endif; ?>
        <?php if(count($booking->transports) > 0): ?>

            <div class="col-lg-12 mb-3">

                <h6><strong>Transport</strong> Detail</h6>

                <table class="table table-bordered table-sm">

                    <tr class="header">

                        <td>

                            <h6 class="mb-0 pb-0">Type</h6>

                        </td>

                        <td>

                            <h6 class="mb-0 pb-0">Transport Airport</h6>

                        </td>

                        <td>

                            <h6 class="mb-0 pb-0">Location</h6>

                        </td>

                        <td>

                            <h6 class="mb-0 pb-0">Time</h6>

                        </td>

                        <td>

                            <h6 class="mb-0 pb-0">Vehical Type</h6>

                        </td>

                    </tr>

                    <?php $__currentLoopData = $booking->transports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e($transport['transport_type']); ?></p>
                            </td>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e($transport['airport']); ?></p>
                            </td>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e($transport['location']); ?></p>
                            </td>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e(date('H:i', strtotime($transport['time']))); ?></p>
                            </td>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e($transport['car_type']); ?></p>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </table>

            </div>

        <?php endif; ?>

        <?php if(count($booking->visas) > 0): ?>

            <div class="col-lg-12 mb-3">

                <h6><strong>Visa</strong> Detail</h6>

                <table class="table table-bordered table-sm">

                    <tr class="header">

                        <td>

                            <h6 class="mb-0 pb-0">Visa Category</h6>

                        </td>

                        <td>

                            <h6 class="mb-0 pb-0">Visa Country</h6>

                        </td>

                    </tr>

                    <?php $__currentLoopData = $booking->visas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e($visa->visa_category); ?></p>
                            </td>
                            <td>
                                <p class="mb-0 pb-0"><?php echo e($visa->visa_country); ?></p>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </table>

            </div>

        <?php endif; ?>

    </div>
    <h6 class="border-bottom border-dark">Passenger(s)</h6>

    <div class="row">

        <div class="col-lg-12">
            <?php $__currentLoopData = $booking->passengers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passenger): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <h6 class="mb-0">
                    <?php echo e($passenger->title . ' ' . $passenger->first_name . ' ' . $passenger->middle_name . ' ' . $passenger->last_name); ?>

                </h6>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>

    <h6 class="border-bottom border-dark mt-5">CHARGES DETAIL</h6>

    <div class="row">

        <div class="col-lg-12">

            <div class="table-responsive">

                <table class="table table-sm">

                    <tbody>

                        <?php $__currentLoopData = $booking->prices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($price->quantity != null): ?>

                            <tr>

                                <th class="mb-0 pb-0"><?php echo e($price->booking_type); ?> </th>

                                <th colspan="3" class="mb-0 pb-0"> <?php echo e($price->quantity . ' * ' . $price->sale_cost); ?></th>

                                <td class="mb-0 pb-0">:</td>

                                <td class="mb-0 pb-0"><?php echo e($booking->currency . $price->total); ?></td>

                            </tr>
                        <?php endif; ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $total_other_charges = 0;
                       ?>
                        <?php if(count($booking->otherCharges) > 0): ?>
                            <?php $__currentLoopData = $booking->otherCharges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $other_charge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $total_other_charges = $total_other_charges + $other_charge->amount;
                            ?>
                                <tr>

                                    <th class="mb-0 pb-0"><?php echo e($other_charge->charges_type); ?> </th>
                                    

                                    <th colspan="3" class="mb-0 pb-0"></th>

                                    <td class="mb-0 pb-0">:</td>

                                    <td class="mb-0 pb-0"><?php echo e($booking->currency . $other_charge->amount); ?> </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>



                        <tr>

                            <th class="mb-0 pb-0">Total</th>

                            <th colspan="3" class="mb-0 pb-0"></th>

                            <td class="mb-0 pb-0">:</td>

                            
                            <td class="mb-0 pb-0"><?php echo e($booking->currency . $booking->total_sales_cost+$total_other_charges); ?> </td>

                        </tr>


                        <tr>

                            <th class="mb-0 pb-0">Paid</th>

                            <th colspan="3" class="mb-0 pb-0"></th>

                            <td class="mb-0 pb-0">:</td>

                            
                            <td class="mb-0 pb-0"><?php echo e($booking->currency . $booking->deposite_amount+$booking->other_charges); ?> </td>

                        </tr>

                        <tr>

                            <th class="mb-0 pb-0">Balance Due</th>

                            <th colspan="3" class="mb-0 pb-0"></th>

                            <td class="mb-0 pb-0">:</td>

                            <td class="mb-0 pb-0"><?php echo e($booking->currency . $booking->balance_amount + ($total_other_charges-$booking->other_charges)); ?> </td>

                        </tr>

                        

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-12">

            <div class="table-responsive">

                <table class="table table-borderless table-sm">

                    <thead>

                        <tr class="border-bottom border-dark">

                            <th colspan="2" class="mb-0 pb-0">

                                <h6 class="mb-0">Payment Date</h6>

                            </th>

                            <th colspan="2" class="mb-0 pb-0">

                                <h6 class="mb-0">Payment Mode</h6>

                            </th>

                            <th colspan="2" class="mb-0 pb-0">

                                <h6 class="mb-0">Amount</h6>

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php $__currentLoopData = $booking->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                            <tr>

                                <th colspan="2" class="mb-0 pb-0"><?php echo e(date("d M Y", strtotime($payment->deposit_date))); ?></th>

                                <td colspan="2" class="mb-0 pb-0"><?php echo e($payment->payment_method); ?></td>

                                <td colspan="2" class="mb-0 pb-0"><?php echo e($booking->currency . $payment->reciving_amount); ?><?php if($payment->cc_charges > 0): ?>
                                    <?php echo e(" (+ ".$payment->cc_charges ." CC)"); ?>

                                <?php endif; ?></td>

                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
    <hr>

    <div class="row">
        <div class="col-lg-12">
            <h6><strong>Please</strong> be advised that all payments should be deposited exclusively in the following banks.</h6>
            <div class="table-responsive">
                <table class="table table-borderless table-sm">

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
            </div>
            <h6><strong>Note: </strong> Payments made to any bank other than those listed above will not be accepted.</h6>
        </div>
    </div>
    <hr>
    <?php if($booking->comments != null): ?>
        <h6 class="border-bottom border-dark">Booking Notes</h6>

        <div class="row">

            <div class="col-lg-12">

                <?php echo e($booking->comments); ?>


            </div>

        </div>
    <?php endif; ?>

    <div class="row">

                <div class="col-lg-12">

                    <h6 class="text-center">Booking Terms & Conditions</h6>

                    <table class="table">

                        <tr>

                            <td>

                                <div class="row">

                                    <div class="col-lg-12">

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

                                        <h6 class="border_bottom"><b>Terms and Conditions</b></h6>


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
                                            <?php if($booking->company->id == 6): ?>
                                                <li>All the authorized payments taken by Seven Zones which is the parent company of HajjUmrah4U.</li>
                                            <?php endif; ?>


                                        </ul>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

</div>
<div class="modal-footer">

    <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i
            class="ri-close-line me-1 align-middle"></i> Close</a>
    

</div>


<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/send-invoice-email-template.blade.php ENDPATH**/ ?>