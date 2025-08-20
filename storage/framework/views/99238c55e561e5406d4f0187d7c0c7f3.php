<form id="pricingForm">
    <?php echo csrf_field(); ?>
    <div class="modal-header border-bottom border-2 py-2 bg-secondary-subtle">
        <h5 class="modal-title" id="fullscreeexampleModalLabel">Edit Pricing | Booking Number:
            <?php echo e($booking->booking_prefix . $booking->booking_number); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <?php if(Auth::user()->role == 5): ?>
        <div class="alert alert-danger mb-xl-0 material-shadow" role="alert">
            <strong> Warning! </strong> You don't have access to this module...
        </div>
    <?php else: ?>
    <div class="modal-body">
        <h6 class="fw-bold text-primary"> Inovice Pricing Details </h6>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Sale Cost<!-- Net Cost --></th>
                        <th>Net Cost<!-- Agent Net Price --></th>
                        <th>Quantity</th>
                        <th>Total Sale Cost</th>
                        <th>Agent Net Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row"><label for="" class="form-label">Adult (Flight Price)</label></th>
                        <input type="hidden" name="booking_pricing[1][type]" value="Adult">
                        <input type="hidden" name="booking_pricing[1][pricing_type]" value="bookingFlight">
                        <input type="hidden" name="booking_pricing[1][id]" value="<?php echo e($booking->prices[0]->id); ?>">
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)"
                                    value="<?php echo e($booking->prices[0]->sale_cost); ?>" id="netCost_1" name="booking_pricing[1][flight_price]"
                                    placeholder="Sale Cost" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_price" onkeyup="calculations(this.id)"
                                    value="<?php echo e($booking->prices[0]->net_cost); ?>" id="netPrice_1" name="booking_pricing[1][flight_net_price]"
                                    placeholder="Net Cost" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                                <input type="number" class="form-control quantity" onkeyup="calculations(this.id)"
                                    value="<?php echo e($booking->prices[0]->quantity); ?>" id="quantity_1" name="booking_pricing[1][quantity]" placeholder="Quantity"
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control total_sale_cost" value="<?php echo e($booking->prices[0]->total); ?>" id="total_1"
                                    placeholder="Total Sale Cost" name="booking_pricing[1][total]" readonly=""
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_total" id="netTotal_1" value="<?php echo e($booking->prices[0]->net_total); ?>"
                                    name="booking_pricing[1][net_total]" placeholder="Net Total" readonly=""
                                    step="0.01">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Youth (Flight Price)</th>
                        <input type="hidden" name="booking_pricing[2][type]" value="Youth">
                        <input type="hidden" name="booking_pricing[2][pricing_type]" value="bookingFlight">
                        <input type="hidden" name="booking_pricing[2][id]" value="<?php echo e($booking->prices[1]->id); ?>">
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[1]->sale_cost); ?>" id="netCost_2" name="booking_pricing[2][flight_price]" placeholder="Sale Cost"
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[1]->net_cost); ?>" id="netPrice_2" name="booking_pricing[2][flight_net_price]" placeholder="Net Cost"
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                                <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[1]->quantity); ?>" id="quantity_2" name="booking_pricing[2][quantity]" placeholder="Quantity"
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control total_sale_cost" id="total_2" value="<?php echo e($booking->prices[1]->total); ?>"
                                    placeholder="Total Sale Cost" name="booking_pricing[2][total]" readonly=""
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_total" id="netTotal_2" value="<?php echo e($booking->prices[1]->net_total); ?>"
                                    placeholder="Net Total" name="booking_pricing[2][net_total]" readonly=""
                                    step="0.01">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Child (Flight Price)</th>
                        <input type="hidden" name="booking_pricing[3][type]" value="Child">
                        <input type="hidden" name="booking_pricing[3][pricing_type]" value="bookingFlight">
                        <input type="hidden" name="booking_pricing[3][id]" value="<?php echo e($booking->prices[2]->id); ?>">
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[2]->sale_cost); ?>" id="netCost_3" name="booking_pricing[3][flight_price]" placeholder="Sale Cost"
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[2]->net_cost); ?>" id="netPrice_3" name="booking_pricing[3][flight_net_price]"
                                    placeholder="Net Cost" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                                <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[2]->quantity); ?>" id="quantity_3" name="booking_pricing[3][quantity]" placeholder="Quantity"
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control total_sale_cost" id="total_3" value="<?php echo e($booking->prices[2]->total); ?>" placeholder="Total Sale Cost" name="booking_pricing[3][total]" readonly=""
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_total" id="netTotal_3" value="<?php echo e($booking->prices[2]->net_total); ?>" placeholder="Net Total" name="booking_pricing[3][net_total]" readonly=""
                                    step="0.01">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Infant(Flight Price)</th>
                        <input type="hidden" name="booking_pricing[4][type]" value="Infant">
                        <input type="hidden" name="booking_pricing[4][pricing_type]" value="bookingFlight">
                        <input type="hidden" name="booking_pricing[4][id]" value="<?php echo e($booking->prices[3]->id); ?>">
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[3]->sale_cost); ?>" id="netCost_4" name="booking_pricing[4][flight_price]" placeholder="Sale Cost"
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[3]->net_cost); ?>" id="netPrice_4" name="booking_pricing[4][flight_net_price]"
                                    placeholder="Net Cost" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                                <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[3]->quantity); ?>" id="quantity_4" name="booking_pricing[4][quantity]" placeholder="Quantity"
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control total_sale_cost" id="total_4" value="<?php echo e($booking->prices[3]->total); ?>" placeholder="Total Sale Cost" name="booking_pricing[4][total]" readonly=""
                                    step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_total" id="netTotal_4" value="<?php echo e($booking->prices[3]->net_total); ?>" placeholder="Net Total" name="booking_pricing[4][net_total]" readonly=""
                                    step="0.01">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Hotel Price</th>
                        <input type="hidden" name="booking_pricing[5][type]" value="Hotel Price">
                        <input type="hidden" name="booking_pricing[5][pricing_type]" value="bookingHotel">
                        <input type="hidden" name="booking_pricing[5][id]" value="<?php echo e($booking->prices[4]->id); ?>">
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[4]->sale_cost); ?>" id="netCost_5" name="booking_pricing[5][flight_price]" placeholder="Sale Cost"
                                    step="0.01" readonly="">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[4]->net_cost); ?>" id="netPrice_5" name="booking_pricing[5][flight_net_price]"
                                    placeholder="Net Cost" step="0.01" readonly="">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                                
                                <input type="number" class="form-control quantity" onkeyup="" id="quantity_5" value="<?php echo e($booking->prices[4]->quantity); ?>" name="booking_pricing[5][quantity]" placeholder="Quantity" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control total_sale_cost" id="total_5" value="<?php echo e($booking->prices[4]->total); ?>" placeholder="Total Sale Cost" name="booking_pricing[5][total]" readonly="" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_total" id="netTotal_5" value="<?php echo e($booking->prices[4]->net_total); ?>" placeholder="Net Total" name="booking_pricing[5][net_total]" readonly="" step="0.01">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Transport Price</th>
                        <input type="hidden" name="booking_pricing[6][type]" value="Transport Price">
                        <input type="hidden" name="booking_pricing[6][pricing_type]" value="bookingTransport">
                        <input type="hidden" name="booking_pricing[6][id]" value="<?php echo e($booking->prices[5]->id); ?>">
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[5]->sale_cost); ?>" id="netCost_6" name="booking_pricing[6][flight_price]" placeholder="Sale Cost" step="0.01" readonly="">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[5]->net_cost); ?>" id="netPrice_6" name="booking_pricing[6][flight_net_price]" placeholder="Net Cost" step="0.01" readonly="">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                                
                                <input type="number" class="form-control quantity" onkeyup="" id="quantity_6" value="<?php echo e($booking->prices[5]->quantity); ?>" name="booking_pricing[6][quantity]" placeholder="Quantity" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control total_sale_cost" id="total_6" value="<?php echo e($booking->prices[5]->total); ?>" placeholder="Total Sale Cost" name="booking_pricing[6][total]" readonly="" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_total" id="netTotal_6" value="<?php echo e($booking->prices[5]->net_total); ?>" placeholder="Net Total" name="booking_pricing[6][net_total]" readonly="" step="0.01">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Visa</th>
                        <input type="hidden" name="booking_pricing[7][type]" value="Visa">
                        <input type="hidden" name="booking_pricing[7][pricing_type]" value="bookingVisa">
                        <input type="hidden" name="booking_pricing[7][id]" value="<?php echo e($booking->prices[6]->id); ?>">
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[6]->sale_cost); ?>" id="netCost_7" name="booking_pricing[7][flight_price]" placeholder="Sale Cost" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[6]->net_cost); ?>" id="netPrice_7" name="booking_pricing[7][flight_net_price]" placeholder="Net Cost" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                                <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" value="<?php echo e($booking->prices[6]->quantity); ?>" id="quantity_7" name="booking_pricing[7][quantity]" placeholder="Quantity" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control total_sale_cost" id="total_7"  value="<?php echo e($booking->prices[6]->total); ?>"  placeholder="Total Sale Cost" name="booking_pricing[7][total]" readonly="" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_total" id="netTotal_7" value="<?php echo e($booking->prices[6]->net_total); ?>" placeholder="Net Total" name="booking_pricing[7][net_total]" readonly="" step="0.01">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">ATOL(fixed)</th>
                        <input type="hidden" name="booking_pricing[8][type]" value="ATOL">
                        <input type="hidden" name="booking_pricing[8][pricing_type]" value="bookingAtol">
                        <input type="hidden" name="booking_pricing[8][id]" value="<?php echo e($booking->prices[7]->id); ?>">
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" value="2.5" id="netCost_8" name="booking_pricing[8][flight_price]" value="<?php echo e($booking->prices[7]->sale_cost); ?>" placeholder="Sale Cost" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" id="netPrice_8" value="2.5" name="booking_pricing[8][flight_net_price]" value="<?php echo e($booking->prices[7]->net_cost); ?>" placeholder="Net Cost" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                                <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" id="quantity_8" value="0" name="booking_pricing[8][quantity]" value="<?php echo e($booking->prices[7]->quantity); ?>" placeholder="Quantity" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control total_sale_cost" id="total_8" placeholder="Total Sale Cost" name="booking_pricing[8][total]" readonly="" value="<?php echo e($booking->prices[7]->total); ?>" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_total" id="netTotal_8" value="<?php echo e($booking->prices[7]->net_total); ?>" placeholder="Net Total" name="booking_pricing[8][net_total]" readonly="" step="0.01">
                            </div>
                        </td>
                    </tr>

                    

                    <tr>
                        <th scope="row">Full Package</th>
                        <input type="hidden" name="booking_pricing[10][type]" value="Full Package">
                        <input type="hidden" name="booking_pricing[10][pricing_type]" value="bookingFullPackage">
                        <input type="hidden" name="booking_pricing[10][id]" value="<?php echo e(isset($booking->prices[9]) ? $booking->prices[9]->id : ''); ?>">
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" value="<?php echo e(isset($booking->prices[9]) ? $booking->prices[9]->sale_cost : ''); ?>"  id="netCost_10" name="booking_pricing[10][flight_price]" placeholder="Sale Cost" step="0.01" readonly>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" value="<?php echo e(isset($booking->prices[9]) ? $booking->prices[9]->net_cost : ''); ?>" id="netPrice_10" name="booking_pricing[10][flight_net_price]" placeholder="Net Cost" step="0.01" readonly>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                                <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" value="<?php echo e(isset($booking->prices[9]) ? $booking->prices[9]->quantity : ''); ?>" id="quantity_10" name="booking_pricing[10][quantity]" placeholder="Quantity" step="0.01" readonly>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control total_sale_cost" id="total_10" value="<?php echo e(isset($booking->prices[9]) ? $booking->prices[9]->total : ''); ?>"  placeholder="Total Sale Cost" name="booking_pricing[10][total]" readonly="" step="0.01">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i
                                        class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control net_total" id="netTotal_10" value="<?php echo e(isset($booking->prices[9]) ? $booking->prices[9]->net_total : ''); ?>"  placeholder="Net Total" name="booking_pricing[10][net_total]" readonly="" step="0.01">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row" colspan="5"><span class="float-end">Total Invoice Amount</span></th>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control total_sales_cost" id="total_sales_cost" name="total_sales_cost" value="<?php echo e($booking->total_sales_cost); ?>" placeholder="Total Sales Cost" readonly required="" step="0.01">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row" colspan="5"><span class="float-end">Net Price</span></th>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control total_net_price" id="total_net_price" value="<?php echo e($booking->total_net_cost); ?>" name="total_net_cost" placeholder="Net Cost" step="0.01" required="" readonly>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row" colspan="5"><span class="float-end">Margin</span></th>
                        <td>
                            <div class="input-group">
                                <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                                <input type="number" class="form-control margin" name="margin" id="margin" value="<?php echo e($booking->margin); ?>" placeholder="Margin" readonly="" required="" step="0.01">
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="booking_id" id="booking_id" value="<?php echo e($booking->id); ?>">
        <button type="submit" class="btn btn-primary btn-sm" id="saveInstallmentPlan"><i class="ri-save-3-line me-1 align-middle"></i>Save</button>
        <a href="javascript:void(0);" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
    </div>
    <?php endif; ?>

</form>

<script>

    function calculations(id){
        var id = id.split('_')[1];
        //console.log(id);
        $row_net_cost = $("#netCost_"+id).val();
        $row_net_price = $("#netPrice_"+id).val();
        $row_quantity = $("#quantity_"+id).val();


        if (id === '5' || id === '6') {
            //console.log('in');
            $row_total_net_cost = $row_net_cost;
            $row_total_net_price = $row_net_price;
        }else{
            //console.log('out');
            $row_total_net_cost = $row_net_cost*$row_quantity
            $row_total_net_price = $row_net_price*$row_quantity;
        }
        //console.log($row_total_net_cost, $row_total_net_price);
        //$row_total_net_cost = $row_net_cost*$row_quantity;
        $('#total_'+id).val($row_total_net_cost);

        //$row_total_net_price = $row_net_price*$row_quantity;
        $('#netTotal_'+id).val($row_total_net_price);

        // .each on total
        $total_sale_cost=$net_total = 0;
        $('.total_sale_cost').each(function(){
            if (!isNaN(this.value) && this.value.length != 0) {
                $total_sale_cost += parseFloat(this.value);
            }
        });

        $('#total_sales_cost').val($total_sale_cost)

        // .each on net_total
        $('.net_total').each(function(){
            if (!isNaN(this.value) && this.value.length != 0) {
                $net_total += parseFloat(this.value);
            }
        });

        $('#total_net_price').val($net_total)

        $margin = $total_sale_cost - $net_total;

        $('#margin').val($margin)

        $('#deposite_amount').attr('max', $total_sale_cost);
        $('#deposite_amount').val($total_sale_cost);
        $('#balance_amount').val('0');
    };

    function isFullPackage(checkbox) {
        if (checkbox == 1) {
            console.log('Checkbox is checked');

            $('.net_cost').each(function(){
                //$(this).val('0');
                $(this).prop('readonly', true);
                $(this).attr('required', false);
            });


            $('#netCost_10').prop('readonly', false);
            $('#netCost_10').attr('required', true);
            $('#quantity_10').prop('readonly', false);

            //$('#netPrice_10').prop('readonly', true);
            // $('#net_cost').prop('readonly', true);

        }

        $('.net_cost').each(function(){
            //console.log(this.id);
            calculations(this.id)
            //calculations(this.id)
        });

        $('.net_price').each(function(){
            //console.log(this.id);
            calculations(this.id)
            //calculations(this.id)
        });
        $('#netPrice_10').prop('readonly', true);

    }

    $(document).ready(function() {

        isFullPackage(<?php echo e($booking->is_full_package); ?>)
        // Validate form before submitting
        $('#pricingForm').submit(function(event) {
            //console.log($(this).serialize());
            // Perform custom validation here

            //var balance_amount = parseFloat($('[name="balance_amount"]').val());
            var booking_id = $('[name="booking_id"]').val();

            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: "<?php echo e(route('crm.update-pricing-details')); ?>",
                data: $(this).serialize(),
                success: function(response) {
                    //console.log(response);
                    /* if (response.code == 200) {
                        $('.modal.extraLargeModal').modal('toggle');
                        $('.modal.fullscreeexampleModal').modal('toggle');
                        view_booking(booking_id);
                    }*/
                    $('.modal.extraLargeModal').modal('toggle');
                    $('.modal.fullscreeexampleModal').modal('toggle');
                    view_booking(booking_id);

                    Swal.fire({
                        title: response.title,
                        icon: response.icon,
                        text: response.message,
                    });
                },
                error: function(xhr, status, error) {
                    //console.log(xhr, status, error);
                    // Show error message
                    //alert(xhr.responseJSON.message);

                    Swal.fire({
                        //title: response.title,
                        //icon: response.icon,
                        text: xhr.responseJSON.message,
                    });
                }
            });
            return;
        });
    });
</script>
<?php /**PATH /home4/bestumr2/public_html/mis.bestumrahpackagesuk.com/resources/views/modules/CRM/booking/modals/edit-pricing.blade.php ENDPATH**/ ?>