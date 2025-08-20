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
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" value="" id="netCost_1" name="booking_pricing[1][flight_price]" placeholder="Sale Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" value="" id="netPrice_1" name="booking_pricing[1][flight_net_price]" placeholder="Net Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                        <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" id="quantity_1" name="booking_pricing[1][quantity]" placeholder="Quantity" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_sale_cost" id="total_1" placeholder="Total Sale Cost" name="booking_pricing[1][total]" readonly="" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_total" id="netTotal_1" name="booking_pricing[1][net_total]" placeholder="Net Total" readonly="" step="0.01">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">Youth (Flight Price)</th>
                <input type="hidden" name="booking_pricing[2][type]" value="Youth">
                <input type="hidden" name="booking_pricing[2][pricing_type]" value="bookingFlight">
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" id="netCost_2" name="booking_pricing[2][flight_price]" placeholder="Sale Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" id="netPrice_2" name="booking_pricing[2][flight_net_price]" placeholder="Net Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                        <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" id="quantity_2" name="booking_pricing[2][quantity]" placeholder="Quantity" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_sale_cost" id="total_2" placeholder="Total Sale Cost" name="booking_pricing[2][total]" readonly="" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_total" id="netTotal_2" placeholder="Net Total" name="booking_pricing[2][net_total]" readonly="" step="0.01">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">Child (Flight Price)</th>
                <input type="hidden" name="booking_pricing[3][type]" value="Child">
                <input type="hidden" name="booking_pricing[3][pricing_type]" value="bookingFlight">
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" id="netCost_3" name="booking_pricing[3][flight_price]" placeholder="Sale Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" id="netPrice_3" name="booking_pricing[3][flight_net_price]" placeholder="Net Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                        <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" id="quantity_3" name="booking_pricing[3][quantity]" placeholder="Quantity" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_sale_cost" id="total_3" placeholder="Total Sale Cost" name="booking_pricing[3][total]" readonly="" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_total" id="netTotal_3" placeholder="Net Total" name="booking_pricing[3][net_total]" readonly="" step="0.01">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">Infant(Flight Price)</th>
                <input type="hidden" name="booking_pricing[4][type]" value="Infant">
                <input type="hidden" name="booking_pricing[4][pricing_type]" value="bookingFlight">
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" id="netCost_4" name="booking_pricing[4][flight_price]" placeholder="Sale Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" id="netPrice_4" name="booking_pricing[4][flight_net_price]" placeholder="Net Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                        <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" id="quantity_4" name="booking_pricing[4][quantity]" placeholder="Quantity" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_sale_cost" id="total_4" placeholder="Total Sale Cost" name="booking_pricing[4][total]" readonly="" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_total" id="netTotal_4" placeholder="Net Total" name="booking_pricing[4][net_total]" readonly="" step="0.01">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">Hotel Price</th>
                <input type="hidden" name="booking_pricing[5][type]" value="Hotel Price">
                <input type="hidden" name="booking_pricing[5][pricing_type]" value="bookingHotel">
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" id="netCost_5" name="booking_pricing[5][flight_price]" placeholder="Sale Cost" step="0.01" readonly="">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" id="netPrice_5" name="booking_pricing[5][flight_net_price]" placeholder="Net Cost" step="0.01" readonly="">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                        
                        <input type="number" class="form-control quantity" onkeyup="" id="quantity_5" name="booking_pricing[5][quantity]" placeholder="Quantity" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_sale_cost" id="total_5" placeholder="Total Sale Cost" name="booking_pricing[5][total]" readonly="" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_total" id="netTotal_5" placeholder="Net Total" name="booking_pricing[5][net_total]" readonly="" step="0.01">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">Transport Price</th>
                <input type="hidden" name="booking_pricing[6][type]" value="Transport Price">
                <input type="hidden" name="booking_pricing[6][pricing_type]" value="bookingTransport">
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" id="netCost_6" name="booking_pricing[6][flight_price]" placeholder="Sale Cost" step="0.01" readonly="">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" id="netPrice_6" name="booking_pricing[6][flight_net_price]" placeholder="Net Cost" step="0.01" readonly="">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                        
                        <input type="number" class="form-control quantity" onkeyup="" id="quantity_6" name="booking_pricing[6][quantity]" placeholder="Quantity" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_sale_cost" id="total_6" placeholder="Total Sale Cost" name="booking_pricing[6][total]" readonly="" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_total" id="netTotal_6" placeholder="Net Total" name="booking_pricing[6][net_total]" readonly="" step="0.01">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">Visa</th>
                <input type="hidden" name="booking_pricing[7][type]" value="Visa">
                <input type="hidden" name="booking_pricing[7][pricing_type]" value="bookingVisa">
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" id="netCost_7" name="booking_pricing[7][flight_price]" placeholder="Sale Cost" step="0.01" readonly="">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" id="netPrice_7" name="booking_pricing[7][flight_net_price]" placeholder="Net Cost" step="0.01" readonly="">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                        <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" id="quantity_7" name="booking_pricing[7][quantity]" placeholder="Quantity" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_sale_cost" id="total_7" placeholder="Total Sale Cost" name="booking_pricing[7][total]" readonly="" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_total" id="netTotal_7" placeholder="Net Total" name="booking_pricing[7][net_total]" readonly="" step="0.01">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">ATOL(fixed)</th>
                <input type="hidden" name="booking_pricing[8][type]" value="ATOL">
                <input type="hidden" name="booking_pricing[8][pricing_type]" value="bookingAtol">
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" value="2.5" id="netCost_8" name="booking_pricing[8][flight_price]" placeholder="Sale Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" id="netPrice_8" value="2.5" name="booking_pricing[8][flight_net_price]" placeholder="Net Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                        <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" id="quantity_8" value="0" name="booking_pricing[8][quantity]" placeholder="Quantity" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_sale_cost" id="total_8" placeholder="Total Sale Cost" name="booking_pricing[8][total]" readonly="" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_total" id="netTotal_8" placeholder="Net Total" name="booking_pricing[8][net_total]" readonly="" step="0.01">
                    </div>
                </td>
            </tr>
            <tr class="d-none">
                <th scope="row">Other Charges</th>
                <input type="hidden" name="booking_pricing[9][type]" value="Other Charges">
                <input type="hidden" name="booking_pricing[9][pricing_type]" value="bookingOtherCharges">
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" id="netCost_9" name="booking_pricing[9][flight_price]" placeholder="Sale Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" id="netPrice_9" name="booking_pricing[9][flight_net_price]" placeholder="Net Cost" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                        <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" id="quantity_9" name="booking_pricing[9][quantity]" placeholder="Quantity" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_sale_cost" id="total_9" placeholder="Total Sale Cost" name="booking_pricing[9][total]" readonly="" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_total" id="netTotal_9" placeholder="Net Total" name="booking_pricing[9][net_total]" readonly="" step="0.01">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">Full Package</th>
                <input type="hidden" name="booking_pricing[10][type]" value="Full Package">
                <input type="hidden" name="booking_pricing[10][pricing_type]" value="bookingFullPackage">
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_cost" onkeyup="calculations(this.id)" id="netCost_10" name="booking_pricing[10][flight_price]" placeholder="Sale Cost" step="0.01" readonly>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_price" onkeyup="calculations(this.id)" id="netPrice_10" name="booking_pricing[10][flight_net_price]" placeholder="Net Cost" step="0.01" readonly>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-team-line fs-5"></i></span>
                        <input type="number" class="form-control quantity" onkeyup="calculations(this.id)" id="quantity_10" name="booking_pricing[10][quantity]" placeholder="Quantity" step="0.01" readonly>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_sale_cost" id="total_10" placeholder="Total Sale Cost" name="booking_pricing[10][total]" readonly="" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control net_total" id="netTotal_10" placeholder="Net Total" name="booking_pricing[10][net_total]" readonly="" step="0.01">
                    </div>
                </td>
            </tr>

            <tr>
                <th scope="row" colspan="5"><span class="float-end">Total Invoice Amount</span></th>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_sales_cost" id="total_sales_cost" name="total_sales_cost" placeholder="Total Sales Cost" readonly required="" step="0.01">
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="5"><span class="float-end">Net Price</span></th>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control total_net_price" id="total_net_price" name="total_net_cost" placeholder="Net Cost" step="0.01" required="" readonly>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="5"><span class="float-end">Margin</span></th>
                <td>
                    <div class="input-group">
                        <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                        <input type="number" class="form-control margin" name="margin" id="margin" placeholder="Margin" readonly="" required="" step="0.01">
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php /**PATH /home/ashraf/Documents/web_dev/mis/resources/views/modules/CRM/booking/inc/booking-pricing.blade.php ENDPATH**/ ?>