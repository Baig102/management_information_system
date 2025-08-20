@extends('layouts.master-crm')
@section('title')
    @lang('translation.crm')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboards
        @endslot
        @slot('title')
            Booking
        @endslot
    @endcomponent

    @section('css')
    <style>
        .tab-pane.fade {
            transition: all 0.2s;
            transform: translateY(1rem);
        }
        .tab-pane.fade.show {
            transform: translateY(0rem);
        }
    </style>
    @endsection
    <form action="{{ route('crm.save-booking') }}" method="post" enctype="multipart/form-data">
        @csrf
        <p @class(['text-danger'])>* Fields Are Required</p>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Booking Information</h4>
                        {{-- <div class="flex-shrink-0">
                            <a href="javascript:void(0)" class="btn btn-soft-success btn-sm float-end btn-label"><i class="ri-user-add-line label-icon align-middle fs-16 me-2"></i> Add </a>
                        </div> --}}
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <label for="company" class="form-label">Select Company<span class="text-danger"> *</span></label>
                                <select class="select2 form-control-sm" id="company" name="company_id" data-placeholder="Select Company" required>
                                    <option></option>
                                    @foreach ($assignedCompanies as $assiCompany)
                                    <option value="{{ $assiCompany->id }}">{{ $assiCompany->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label for="booking_date" class="form-label">Booking Date<span class="text-danger"> *</span></label>
                                <input type="text" name="booking_date" value="{{ date('Y-m-d') }}" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="booking_date" autocomplete="off" required>
                            </div>

                            <div class="col-lg-2">
                                <label for="ticket_deadline" class="form-label">Ticketing Deadline<span class="text-danger"> *</span></label>
                                <input type="text" name="ticket_deadline" value="{{ date('Y-m-d') }}" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="ticket_deadline" autocomplete="off" required>
                            </div>

                            <div class="col-lg-2 col-sm-6">
                                <label class="form-label">Non Refundable Ticket</label>
                                <div class="form-check card-radio">
                                    <input id="non_refundable" name="booking_payment_term" type="radio" class="form-check-input" value="1" checked>
                                    <label class="form-check-label p-2" for="non_refundable">
                                        <span class="fs-16 text-muted me-2"><i class="ri-secure-payment-line align-bottom"></i></span>
                                        <span class="fs-14 text-wrap">Non Refundable</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <label class="form-label">Refundable Ticket</label>
                                <div class="form-check card-radio">
                                    <input id="refundable" name="booking_payment_term" type="radio" class="form-check-input" value="2">
                                    <label class="form-check-label p-2" for="refundable">
                                        <span class="fs-16 text-muted me-2"><i class="ri-refund-line align-bottom"></i></span>
                                        <span class="fs-14 text-wrap">Refundable</span>
                                    </label>
                                </div>
                            </div>
                            {{-- <div class="col-lg-2 col-sm-6">
                                <label class="form-label">Is Full Package</label>
                                <div class="form-check card-radio">
                                    <input id="refundable" name="booking_payment_term" type="radio" class="form-check-input" value="2">
                                    <label class="form-check-label p-2" for="refundable">
                                        <span class="fs-16 text-muted me-2"><i class="ri-refund-line align-bottom"></i></span>
                                        <span class="fs-14 text-wrap">Refundable</span>
                                    </label>
                                </div>
                            </div> --}}
                            <div class="col-lg-2 col-sm-6">
                                <label for="is_full_package" class="form-label">Is Full Package?</label>
                                <div class="form-check form-switch form-switch-custom form-switch-primary">
                                    <input class="form-check-input" type="checkbox" name="is_full_package" role="switch" id="is_full_package" onchange="isFullPackage(this)">
                                    <label class="form-check-label" for="is_full_package">Yes</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Passenger Details</h4>
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0)" onclick="add_passenger_rows()" class="btn btn-soft-success btn-sm float-end btn-label"><i class="ri-shield-user-line label-icon align-middle fs-16 me-2"></i> Add Passenger</a>
                        </div>
                    </div><!-- end card header -->
                    <div class="card-body"  id="passenger_details">
                        <input type="hidden" name="passenger_count" id="passenger_details_count" value="1">
                    </div>
                </div>
            </div>
        </div>

        {{-- Hotel Details --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Hotel Details</h4>
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0)" onclick="add_hotel_rows()"
                                class="btn btn-soft-success btn-sm float-end btn-label"><i
                                    class="ri-hotel-line label-icon align-middle fs-16 me-2"></i> Add Hotel</a>
                            <!--onclick="add_hotel_rows()"-->
                        </div>
                    </div><!-- end card header -->
                    <div class="card-body" id="hotel_details">
                        <input type="hidden" name="hotel_count" id="hotel_details_count" value="1">
                    </div>
                </div>
            </div>
        </div>

        {{-- Transport Details --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Transport Details</h4>
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0)" onclick="add_transport_rows()"
                                class="btn btn-soft-success btn-sm float-end btn-label"><i
                                    class="ri-taxi-fill label-icon align-middle fs-16 me-2"></i> Add Transport</a>
                        </div>
                    </div><!-- end card header -->
                    <div class="card-body" id="transport_details">
                        <input type="hidden" name="transport_count" id="transport_details_count" value="1">

                    </div>
                </div>
            </div>
        </div>

        {{-- Flight Details --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Flight Details</h4>
                        {{-- <div class="bg-light px-3 py-2 rounded-2 mx-4 border-1 bg-light-subtle border-dashed border-primary">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="trip_type" id="one_way" value="1" checked="">
                                    <label class="form-check-label" for="one_way">One Way</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="trip_type" id="return" value="2">
                                    <label class="form-check-label" for="return">Return</label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-light px-3 py-2 rounded-2 mx-4 border-1 bg-light-subtle border-dashed border-warning">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="flight_type" id="direct" value="1" checked="">
                                    <label class="form-check-label" for="direct">Direct</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="flight_type" id="in_direct" value="2">
                                    <label class="form-check-label" for="in_direct">In Direct</label>
                                </div>
                            </div>
                        </div> --}}
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0)" onclick="add_flight_rows()"
                                class="btn btn-soft-success btn-sm float-end btn-label"><i
                                    class="ri-flight-takeoff-line label-icon align-middle fs-16 me-2"></i> Add Flight</a>
                        </div>
                    </div><!-- end card header -->
                    <div class="card-header bg-light-subtle border-bottom-dashed">
                        <div class="row">
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-check card-radio">
                                    <input id="one_way" name="trip_type" type="radio" class="form-check-input"
                                        value="1" checked>
                                    <label class="form-check-label p-2" for="one_way">
                                        <span class="fs-16 text-muted me-2"><i
                                                class="ri-flight-takeoff-line align-bottom"></i></span>
                                        <span class="fs-14 text-wrap">One Way</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-check card-radio">
                                    <input id="return" name="trip_type" type="radio" class="form-check-input"
                                        value="2">
                                    <label class="form-check-label p-2" for="return">
                                        <span class="fs-16 text-muted me-2"><i
                                                class="ri-flight-land-line align-bottom"></i></span>
                                        <span class="fs-14 text-wrap">Return</span>
                                    </label>
                                </div>
                            </div>


                            <div class="col-lg-2 col-sm-6">
                                <div class="form-check card-radio">
                                    <input id="direct" name="flight_type" type="radio" class="form-check-input"
                                        value="1" checked>
                                    <label class="form-check-label p-2" for="direct">
                                        <span class="fs-16 text-muted me-2"><i
                                                class="ri-guide-line align-bottom"></i></span>
                                        <span class="fs-14 text-wrap">Direct</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-check card-radio">
                                    <input id="in_direct" name="flight_type" type="radio" class="form-check-input"
                                        value="2">
                                    <label class="form-check-label p-2" for="in_direct">
                                        <span class="fs-16 text-muted me-2"><i
                                                class="ri-route-line align-bottom"></i></span>
                                        <span class="fs-14 text-wrap">In Direct</span>
                                    </label>
                                </div>
                            </div>

                            <!-- <div class="col-lg-2 col-md-6">
                                <label>Supplier<span class="text-danger"> *</span></label>
                                <select class="ticket_supplier select2" id="ticket_supplier" name="ticket_supplier_id" data-placeholder="Select Ticket Supplier" required></select>
                            </div> -->

                        </div>
                    </div>
                    <div class="card-body" id="flight_details">
                        <input type="hidden" name="flight_count" id="flight_details_count" value="1">

                    </div>
                </div>
            </div>
        </div>

        {{-- Pricing Breakdown --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Pricing Beakdown</h4>
                    </div><!-- end card header -->
                    <div class="card-body" id="pricing_breakdown_details">
                        @include('modules.CRM.booking.inc.booking-pricing')
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Terms --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Payment Terms & Method</h4>
                        {{-- <div class="flex-shrink-0">
                            <a href="javascript:void(0)" onclick="add_transport_rows()"
                                class="btn btn-soft-success btn-sm float-end btn-label"><i
                                    class="ri-taxi-fill label-icon align-middle fs-16 me-2"></i> Add Transport</a>
                        </div> --}}
                        {{-- <div class="flex-shrink-0 ms-2">
                            <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <div data-bs-toggle="tab" data-bs-target="#cash_transfer" role="tab" aria-selected="true">
                                        <div class="form-check card-radio">
                                            <input id="cash_transfer_label" name="payment_method" type="radio" class="form-check-input" value="Cash" checked>
                                            <label class="form-check-label p-2" for="cash_transfer_label">
                                                <span class="fs-16 text-muted me-2"><i class="ri-money-pound-circle-line align-bottom"></i></span>
                                                <span class="fs-14 text-wrap">Cash</span>
                                            </label>
                                        </div>
                                    </div>

                                </li>
                                <li class="nav-item" role="presentation">
                                    <div data-bs-toggle="tab" data-bs-target="#bank_transfer" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="form-check card-radio">
                                            <input id="bank_transfer_label" name="payment_method" type="radio" value="Bank Transfer" class="form-check-input">
                                            <label class="form-check-label p-2" for="bank_transfer_label">
                                                <span class="fs-16 text-muted me-2"><i class="ri-bank-line align-bottom"></i></span>
                                                <span class="fs-14 text-wrap">Bank Transfer</span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <div data-bs-toggle="tab" data-bs-target="#credit_limit_transfer" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="form-check card-radio">
                                            <input id="credit_limit_transfer_label" name="payment_method" type="radio" value="Credit Limit" class="form-check-input">
                                            <label class="form-check-label p-2" for="credit_limit_transfer_label">
                                                <span class="fs-16 text-muted me-2"><i class="ri-secure-payment-line align-bottom"></i></span>
                                                <span class="fs-14 text-wrap">Credit Limit</span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <div data-bs-toggle="tab" data-bs-target="#credit_debit_card_transfer" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="form-check card-radio">
                                            <input id="credit_debit_card_transfer_label" name="payment_method" value="Credit Debit Card" type="radio" class="form-check-input">
                                            <label class="form-check-label p-2" for="credit_debit_card_transfer_label">
                                                <span class="fs-16 text-muted me-2"><i class="ri-bank-card-fill align-bottom"></i></span>
                                                <span class="fs-14 text-wrap">Credit / Debit Card</span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div> --}}

                        <div class="flex-shrink-0 ms-2">
                            <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <div data-bs-toggle="tab" data-bs-target="#cash_transfer" role="tab" aria-selected="false">
                                        <div class="form-check card-radio">
                                            <input id="cash_transfer_label" name="payment_method" type="radio" class="form-check-input" value="Cash">
                                            <label class="form-check-label p-2" for="cash_transfer_label">
                                                <span class="fs-16 text-muted me-2"><i class="ri-money-pound-circle-line align-bottom"></i></span>
                                                <span class="fs-14 text-wrap">Cash</span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <div data-bs-toggle="tab" data-bs-target="#bank_transfer" role="tab" aria-selected="true">
                                        <div class="form-check card-radio">
                                            <!-- Set 'checked' attribute here -->
                                            <input id="bank_transfer_label" name="payment_method" type="radio" value="Bank Transfer" class="form-check-input" checked>
                                            <label class="form-check-label p-2" for="bank_transfer_label">
                                                <span class="fs-16 text-muted me-2"><i class="ri-bank-line align-bottom"></i></span>
                                                <span class="fs-14 text-wrap">Bank Transfer</span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <div data-bs-toggle="tab" data-bs-target="#credit_limit_transfer" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="form-check card-radio">
                                            <input id="credit_limit_transfer_label" name="payment_method" type="radio" value="Credit Limit" class="form-check-input">
                                            <label class="form-check-label p-2" for="credit_limit_transfer_label">
                                                <span class="fs-16 text-muted me-2"><i class="ri-secure-payment-line align-bottom"></i></span>
                                                <span class="fs-14 text-wrap">Credit Limit</span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <div data-bs-toggle="tab" data-bs-target="#credit_debit_card_transfer" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="form-check card-radio">
                                            <input id="credit_debit_card_transfer_label" name="payment_method" value="Credit Debit Card" type="radio" class="form-check-input">
                                            <label class="form-check-label p-2" for="credit_debit_card_transfer_label">
                                                <span class="fs-16 text-muted me-2"><i class="ri-bank-card-fill align-bottom"></i></span>
                                                <span class="fs-14 text-wrap">Credit / Debit Card</span>
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div><!-- end card header -->
                    <div class="card-body" id="payment_terms">
                        <div class="row clearfix" id="installment">
                            <div class="col-lg-2 col-md-6">
                                {{-- <label>Select Currency</label> --}}
                                <select class="form-control ms select2" name="currency"
                                    data-placeholder="Select currency" required>
                                    <option></option>
                                    <option value="Rs">Rs</option>
                                    <option value="$">$</option>
                                    <option value="£" selected>£</option>
                                    <option value="€">€</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-check card-radio">
                                    <input id="full_payment" name="payment_type" type="radio" class="form-check-input"
                                        value="1" checked>
                                    <label class="form-check-label p-2" for="full_payment">
                                        <span class="fs-16 text-muted me-2"><i
                                                class="ri-wallet-3-line align-bottom"></i></span>
                                        <span class="fs-14 text-wrap">Full Payment</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="form-check card-radio">
                                    <input id="instalment" name="payment_type" type="radio" class="form-check-input"
                                        value="2">
                                    <label class="form-check-label p-2" for="instalment">
                                        <span class="fs-16 text-muted me-2"><i
                                                class="ri-install-line align-bottom"></i></span>
                                        <span class="fs-14 text-wrap">Instalment</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 no_of_installments d-none">
                                <select class="form-control ms select2" name="total_installment" id="total_installment" data-placeholder="Select Number of Installments">
                                    <option></option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label>Deposite Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                                    <input type="number" class="form-control deposite_amount" id="deposite_amount" name="deposite_amount" onkeyup="calculate_balance_amount()" placeholder="Enter Deposite Amount" step="0.01" required readonly>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label>Balance Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-money-pound-circle-line fs-5"></i></span>
                                    <input type="number" class="form-control balance_amount" id="balance_amount" name="balance_amount" placeholder="Enter Balance Amount" step="0.01" required readonly>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label>Deposit Date</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                    <input type="text" name="deposit_date" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="deposit_date" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label>Receipt Voucher <span id="receipt_voucher_required" class="text-danger d-none">*</span></label>
                                <div class="input-group">
                                    <input class="form-control" type="file" id="receipt_voucher" name="receipt_voucher">
                                </div>
                            </div>
                        </div>

                        <!-- Tab panes -->
                        {{-- <div class="tab-content">

                            <div class="tab-pane" id="bank_transfer" role="tabpanel">
                                <div class="row gy-3">
                                    <div class="col-lg-4">
                                        <label for="cc-name" class="form-label">Bank Name</label>
                                        <input type="text" name="bank_name" class="form-control" id="cc-name" placeholder="Enter name">
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="credit_debit_card_transfer" role="tabpanel">
                                <div class="row gy-3">
                                    <div class="col-lg-2">
                                        <label for="card_type" class="form-label">Card Type</label>
                                        <input type="text" name="card_type" class="form-control" id="card_type" placeholder="Card Type" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="cc-name" class="form-label">Name on card</label>
                                        <input type="text" name="cc-name" class="form-control" id="cc-name" placeholder="Enter name">
                                        <small class="text-danger">Full name as displayed on card</small>
                                    </div>

                                    <div class="col-lg-2">
                                        <label for="cc-number" class="form-label">Card number</label>
                                        <input type="text" name="cc-number" class="form-control" id="cc-number" placeholder="xxxx xxxx xxxx xxxx">
                                    </div>

                                    <div class="col-lg-2">
                                        <label for="cc-expiration" class="form-label">Expiration</label>
                                        <input type="text" name="cc-expiration" class="form-control" placeholder="MM/YY" id="cc-expiration">
                                    </div>

                                    <div class="col-lg-2">
                                        <label for="cc-cvv" class="form-label">CVV</label>
                                        <input type="text" name="cc-cvv" class="form-control" id="cc-cvv" placeholder="xxx" max="3">
                                    </div>

                                    <div class="col-lg-2">
                                        <label for="cc-ccc" class="form-label">CC Charges</label>
                                        <input type="number" name="cc-ccc" class="form-control" id="cc-ccc" placeholder="xxx" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <div class="tab-content">
                            <!-- Removed 'show active' from other tabs and added it to 'bank_transfer' -->
                            <div class="tab-pane show active" id="bank_transfer" role="tabpanel">
                                <div class="row gy-3">
                                    <div class="col-lg-4">
                                        <label for="cc-name" class="form-label">Bank Name</label>
                                        <input type="text" name="bank_name" class="form-control" id="cc-name" placeholder="Enter name">
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="cash_transfer" role="tabpanel">
                                <!-- Content for Cash Transfer, if any -->
                            </div>

                            <div class="tab-pane" id="credit_debit_card_transfer" role="tabpanel">
                                <div class="row gy-3">
                                    <div class="col-lg-2">
                                        <label for="card_type" class="form-label">Card Type</label>
                                        <input type="text" name="card_type" class="form-control" id="card_type" placeholder="Card Type" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="cc-name" class="form-label">Name on card</label>
                                        <input type="text" name="cc-name" class="form-control" id="cc-name" placeholder="Enter name">
                                        <small class="text-danger">Full name as displayed on card</small>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="cc-number" class="form-label">Card number</label>
                                        <input type="text" name="cc-number" class="form-control" id="cc-number" placeholder="xxxx xxxx xxxx xxxx">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="cc-expiration" class="form-label">Expiration</label>
                                        <input type="text" name="cc-expiration" class="form-control" placeholder="MM/YY" id="cc-expiration">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="cc-cvv" class="form-label">CVV</label>
                                        <input type="text" name="cc-cvv" class="form-control" id="cc-cvv" placeholder="xxx" max="3">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="cc-ccc" class="form-label">CC Charges</label>
                                        <input type="number" name="cc-ccc" class="form-control" id="cc-ccc" placeholder="xxx" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- PNR & Comments --}}
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">PNR Code</h4>
                    </div><!-- end card header -->
                    <div class="card-body" id="flight_pnr">
                        <textarea name="flight_pnr" id="flight_pnr" class="ckeditor-classic"></textarea>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Booking Comments <small class="text-danger"> These comments are visible on invoice for customer</small></h4>
                    </div><!-- end card header -->
                    <div class="card-body" id="comments">
                        <textarea name="comments" id="editor_1" class="form-control" rows="13"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="text-end">
                <button type="submit" class="btn btn-primary btn-label waves-effect waves-light"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save Booking</button>
            </div>
            </div>
        </div>

    </form>

@endsection
@section('filters-offcanvas')
    <!-- Filters -->
    @include('modules.CRM.booking.inc.filters-offcanvas')

@endsection
@section('script')
    <script>
        $(function() {
            $('.select2').select2();
            $(".flatpickr-date").flatpickr({
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });
            //$('select #ticket_supplier').select2('destroy');
            initializeSelect2WithAjax('#ticket_supplier', '{{ route('crm.get-vendors','Ticket') }}', 'Search for Ticket Supplier');

        })

        $(document).ready(function () {
            // Show the corresponding tab when a radio button is clicked
            $('input[name="payment_method"]').on('change', function () {
                var target = $(this).closest('div[data-bs-target]').data('bs-target'); // Get the target tab content ID
                // Hide all tab panes
                $('.tab-pane').removeClass('show active');
                // Show the selected tab pane
                $(target).addClass('show active');
            });
        });

        /* var cleaveDate = new Cleave('.cleave-date', {
            date: true,
            delimiter: '-',
            datePattern: ['d', 'm', 'Y']
        }); */

        if ($('.cleave-date').length) {
            new Cleave('.cleave-date', {
                date: true,
                delimiter: '-',
                datePattern: ['d', 'm', 'Y']
            });
        }

        var cleaveCcE = new Cleave('#cc-expiration', {
            date: true,
            delimiter: '/',
            datePattern: ['m', 'y']
        });

        var cleaveCcN = new Cleave('#cc-number', {
            creditCard: true,
            onCreditCardTypeChanged: function (type) {
                // update UI ...
                //console.log(type);
                $('#card_type').val(type);
            }
        });

        $(document).ready(function() {
            /* $('#is_full_package').change(function() {
                console.log(this);
                if ($(this).is(':checked')) {
                    // Checkbox is checked
                    $('#sales_cost').prop('readonly', false);
                    $('#net_cost').prop('readonly', true);
                } else {
                    // Checkbox is unchecked
                    $('#sales_cost').prop('readonly', true);
                    $('#net_cost').prop('readonly', true);
                }
            }); */
        });

        function isFullPackage(checkbox) {
            if ($(checkbox).is(':checked')) {
                //console.log('Checkbox is checked');

                $('.net_cost').each(function(){
                    $(this).val('0');
                    $(this).prop('readonly', true);
                });

                $('.hotel_sale_cost').each(function(){
                    $(this).val('0');
                    $(this).prop('readonly', true);
                    $(this).attr('required', false);
                });
                $('.transport_sale_cost').each(function(){
                    $(this).val('0');
                    $(this).prop('readonly', true);
                    $(this).attr('required', false);
                });

                $('#netCost_10').prop('readonly', false);
                $('#quantity_10').prop('readonly', false);

                //$('#netPrice_10').prop('readonly', true);
                // $('#net_cost').prop('readonly', true);



            } else {

                //console.log('Checkbox is unchecked');

                $('.net_cost').each(function(){
                    $(this).val('0');
                    $(this).prop('readonly', false);
                });

                $('.hotel_sale_cost').each(function(){
                    $(this).val('0');
                    $(this).prop('readonly', false);
                    $(this).attr('required', true);
                });

                $('.transport_sale_cost').each(function(){
                    $(this).val('0');
                    $(this).prop('readonly', false);
                    $(this).attr('required', true);
                });

                $('#netCost_10').prop('readonly', true);
                $('#netCost_10').val('0');
                $('#quantity_10').prop('readonly', true);
                $('#quantity_10').val('0');

                // $('#netPrice_10').prop('readonly', true);
                // $('#sales_cost').prop('readonly', true);
                // $('#net_cost').prop('readonly', true);
            }
            calculateHotelPricing();
            calculateTransportPricing();

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

        // Additional function to check the state of the checkbox and pass it to isFullPackage
        function checkCheckboxState() {

            var checkbox = $('#is_full_package')[0];
            var isChecked = $(checkbox).is(':checked');
            //console.log(isChecked);
            if (isChecked == true) {
                isFullPackage(checkbox);
                return isChecked;
            }
            // Pass the checkbox state to isFullPackage
        }

        function add_passenger_rows() {
            pass_count = $('#passenger_details_count').val();
            //console.log(pass_count);
            const $passengerDetails = $('#passenger_details');
            //$passengerDetails.empty();

            if (pass_count == 1) {
                var filed_condition = 'required';
                var required_check = '<span class="text-danger"> *</span>';
            } else {
                var filed_condition = '';
                var required_check = '';
            }

            // Create a div for each date
            const $div = $(`<div class="row clearfix passenger_info mb-3" id="passenger_info_${pass_count}"></div>`);

            // Create date input field
            const $dateInput = $(`
                <div class="col-lg-2">
                    <label for="title_${pass_count}" class="form-label">Title<span class="text-danger"> *</span></label>
                    <select class="select2 form-control-sm" id="title_${pass_count}" name="passenger[${pass_count}][title]">
                        <option value="Mr.">Mr.</option>
                        <option value="Ms.">Ms.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Miss.">Miss.</option>
                        <option value="Mstr.">Mstr.</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label for="first_name_${pass_count}" class="form-label">First Name<span class="text-danger"> *</span></label>
                    <input type="text" name="passenger[${pass_count}][first_name]" value="" class="form-control" placeholder="First Name" id="first_name_${pass_count}" autocomplete="off" required>
                </div>
                <div class="col-lg-2">
                    <label for="middle_name_${pass_count}" class="form-label">Middle Name</label>
                    <input type="text" name="passenger[${pass_count}][middle_name]" value="" class="form-control" placeholder="Middle Name" id="middle_name_${pass_count}" autocomplete="off">
                </div>
                <div class="col-lg-2">
                    <label for="last_name_${pass_count}" class="form-label">Last Name</label>
                    <input type="text" name="passenger[${pass_count}][last_name]" value="" class="form-control" placeholder="Last Name" id="last_name_${pass_count}" autocomplete="off">
                </div>
                <div class="col-lg-2">
                    <label for="date_of_birth_${pass_count}" class="form-label">Date of Birth</label>
                    <input type="text" name="passenger[${pass_count}][date_of_birth]" value="" class="form-control cleave-date" placeholder="DD-MM-YYYY" id="date_of_birth_${pass_count}" autocomplete="off">
                </div>
                <div class="col-lg-2">
                    <label for="nationality_${pass_count}" class="form-label">Nationality</label>
                    <input type="text" name="passenger[${pass_count}][nationality]" value="" class="form-control" placeholder="Nationality" id="nationality_${pass_count}" autocomplete="off">
                </div>
                <div class="col-lg-2 mt-2">
                    <label for="mobile_number_${pass_count}" class="form-label">Mobile Number${required_check}</label>
                    <input type="text" name="passenger[${pass_count}][mobile_number]" value="" class="form-control chose_multi" placeholder="Mobile Number" id="mobile_number_${pass_count}" autocomplete="off" ${filed_condition}>
                </div>
                <div class="col-lg-2 mt-2">
                    <label for="email_${pass_count}" class="form-label">Email${required_check}</label>
                    <input type="text" name="passenger[${pass_count}][email]" value="" class="form-control chose_multi" placeholder="Email" id="email_${pass_count}" data-choices data-choices-limit="10" data-choices-removeItem autocomplete="off" ${filed_condition}>
                </div>
                <div class="col-lg-2 mt-2">
                    <label for="address_${pass_count}" class="form-label">Address${required_check}</label>
                    <input type="text" name="passenger[${pass_count}][address]" value="" class="form-control" placeholder="Address" id="address_${pass_count}" autocomplete="off"  ${filed_condition}>
                </div>
                <div class="col-lg-2 mt-2">
                    <label for="post_code_${pass_count}" class="form-label">Post Code</label>
                    <input type="text" name="passenger[${pass_count}][post_code]" value="" class="form-control" placeholder="Post Code" id="post_code_${pass_count}" autocomplete="off">
                </div>
                <div class="col-lg-2 mt-2">
                    <label for="ticket_number_${pass_count}" class="form-label">Ticket Number</label>
                    <input type="number" name="passenger[${pass_count}][ticket_number]" value="" class="form-control" placeholder="Ticket Number" id="ticket_number_${pass_count}" autocomplete="off">
                </div>
                <div class="col-lg-2 mt-2 d-none">
                    <label for="pnr_code_${pass_count}" class="form-label">PNR Code</label>
                    <input type="number" name="passenger[${pass_count}][pnr_code]" value="" class="form-control" placeholder="PNR Code" id="pnr_code_${pass_count}" autocomplete="off">
                </div>
                <div class="col-lg-2 mt-3">
                    <button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(passenger_info_${pass_count})"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
                </div>
                <hr class="mt-4">
            `);
            $div.append($dateInput);

            // Append the div to the container
            $passengerDetails.append($div);
            var cleaveDate = new Cleave('#date_of_birth_'+pass_count, {
                date: true,
                delimiter: '-',
                datePattern: ['d', 'm', 'Y']
            });

            $("#title_"+pass_count).select2();


            $('#passenger_details_count').val(parseInt(pass_count) + +1);

            //initializeSelect2WithAjax('#hotelSupplier_'+ hotel_count, '{{ route('ams.get-vendors','Hotels') }}', 'Search for Vendor');
        };

        function add_hotel_rows() {

            // var checkbox = $('#is_full_package')[0];
            // var isChecked = $(checkbox).is(':checked');
            // // Pass the checkbox state to isFullPackage
            // isFullPackage(checkbox);



            hotel_count = $('#hotel_details_count').val();

            const $hotelContainer = $('#hotel_details');
            //$hotelContainer.empty();

            // Create a div for each date
            const $div = $(`<div class="row clearfix hotel_info mb-3" id="hotel_info_${hotel_count}"></div>`);

            // Create date input field
            const $dateInput = $(`
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Hotel Name<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" name="hotel[${hotel_count}][hotel_name]" value="" placeholder="Hotel Name" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Check in date<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control flatpickr check_in_date" id="check_in_date_${hotel_count}" name="hotel[${hotel_count}][check_in_date]" value="" placeholder="Check in date" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Check out Date<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control flatpickr check_out_date" id="check_out_date_${hotel_count}" name="hotel[${hotel_count}][check_out_date]" value="" placeholder="Check out date" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Total nights<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control total_nights" id="total_nights_${hotel_count}" name="hotel[${hotel_count}][total_nights]" value="" placeholder="Total nights" readonly="" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Meal Type<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" name="hotel[${hotel_count}][meal_type]" value="" placeholder="Meal Type" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Room Type<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" name="hotel[${hotel_count}][room_type]" value="" placeholder="Room Type" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>HCN <small>Hotel Confirmation Number</small></label>
                    <input type="text" class="form-control" name="hotel[${hotel_count}][hotel_confirmation_number]" value="" placeholder="HCN">
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Sale Cost<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control hotel_sale_cost" name="hotel[${hotel_count}][sale_cost]" value="" placeholder="Sale Cost" step="0.01" required="" onkeyup="calculateHotelPricing()">
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Net Cost<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control hotel_net_cost" name="hotel[${hotel_count}][net_cost]" value="" placeholder="Net Cost" step="0.01" required="" onkeyup="calculateHotelPricing()">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label>Supplier<span class="text-danger"> *</span></label>
                    <select class="hotel_supplier" id="hotelSupplier_${hotel_count}" name="hotel[${hotel_count}][supplier]" data-placeholder="select hotel supplier" required></select>

                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(hotel_info_${hotel_count}, 'hotel')"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
                </div>
            `);
            $div.append($dateInput);

            $(document).ready(function() {
                // Initialize Flatpickr for the start date input
                var startDatePicker = flatpickr("#check_in_date_" + hotel_count, {
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    onChange: function(selectedDates, dateStr, instance) {
                        // Set the selected date of the start date input as the min date for the end date input
                        endDatePicker.set('minDate', selectedDates[0]);
                        // Calculate and display the date difference
                        calculateAndDisplayDateDifference();
                    }
                });

                // Initialize Flatpickr for the end date input
                var endDatePicker = flatpickr("#check_out_date_" + hotel_count, {
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    onChange: function(selectedDates, dateStr, instance) {
                        // Set the selected date of the end date input as the max date for the start date input
                        startDatePicker.set('maxDate', selectedDates[0]);
                        // Calculate and display the date difference
                        calculateAndDisplayDateDifference();
                    }
                });

                // Function to calculate and display the date difference
                function calculateAndDisplayDateDifference() {
                    var startDate = startDatePicker.selectedDates[0];
                    var endDate = endDatePicker.selectedDates[0];

                    if (startDate && endDate) {
                        var dateDifference = Math.abs(endDate - startDate);
                        var daysDifference = Math.ceil(dateDifference / (1000 * 60 * 60 * 24));
                        $('#total_nights_' + hotel_count).val(daysDifference);
                        // Display the date difference
                        console.log(hotel_count, "Date Difference: " + daysDifference + " days");
                    }
                }
            });

            // Append the div to the container
            $hotelContainer.append($div);

            $('#hotel_details_count').val(parseInt(hotel_count) + +1);

            initializeSelect2WithAjax('#hotelSupplier_'+ hotel_count, '{{ route('crm.get-vendors','Hotels') }}', 'Search for Vendor');
            checkCheckboxState();
        };

        function add_transport_rows() {

            //$('.select').select2();

            transport_count = $('#transport_details_count').val();

            $("#transport_info_" + transport_count + " select").select2('destroy');

            const $transportContainer = $('#transport_details');
            //$transportContainer.empty();

            // Create a div for each date
            const $div = $(`<div class="row clearfix transport_info mb-3" id="transport_info_${transport_count}"></div>`);

            // Create date input field
            const $dateInput = $(`
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Transport Type<span class="text-danger"> *</span></label>
                    <select class="select2 form-control form-control-sm" name="transport[${transport_count}][type]" required="" data-placeholder="select">
                        <option></option>
                        <option value="Arrival">Arrival</option>
                        <option value="Departure">Departure</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Airport<span class="text-danger"> *</span></label>
                    <select class="airports" id="transportAirport_${transport_count}" name="transport[${transport_count}][airport]" required></select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Location<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="transportLocation${transport_count}" name="transport[${transport_count}][location]" value="" placeholder="Location" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Time<span class="text-danger"> *</span></label>
                    <input type="time" class="form-control time" name="transport[${transport_count}][time]" value="" placeholder="Time" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Car Type<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control" id="transportCarType${transport_count}" name="transport[${transport_count}][car_type]" value="" placeholder="Car Type" required>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Sale Cost<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control transport_sale_cost" name="transport[${transport_count}][sale_cost]" value="" placeholder="Sale Cost" step="0.01" required="" onkeyup="calculateTransportPricing()">
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <label>Net Cost<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control transport_net_cost" name="transport[${transport_count}][net_cost]" value="" placeholder="Net Cost" step="0.01" required="" onkeyup="calculateTransportPricing()">
                </div>

                <div class="col-lg-2 col-md-6">
                    <label>Supplier<span class="text-danger"> *</span></label>
                    <select class="transport_supplier" id="transportSupplier_${transport_count}" name="transport[${transport_count}][supplier]" data-placeholder="select transport supplier" required></select>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <button type="button" class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(transport_info_${transport_count}, 'transport')"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
                    </div>
                </div>
            `);

            $div.append($dateInput);
            // Append the div to the container
            $transportContainer.append($div);
            $("#transport_info_" + transport_count + " select").select2();

            $('#transport_details_count').val(parseInt(transport_count) + +1);

            initializeSelect2WithAjax('#transportSupplier_' + transport_count, '{{ route('crm.get-vendors') }}', 'Search for Vendor');
            initializeSelect2WithAjax('#transportAirport_' + transport_count, '{{ route('crm.get-airports') }}', 'Search for airports');

            checkCheckboxState();
        };

        function add_flight_rows() {

            //$('.select').select2();
            //$flight_info_cont = ('.flight_info').count();

            //console.log($flight_info_cont);

            flight_count = $('#flight_details_count').val();

            $("#flight_info_" + flight_count + " select").select2('destroy');

            const $flightContainer = $('#flight_details');
            //$flightContainer.empty();

            // Create a div for each date
            const $div = $(`<div class="row clearfix flight_info mb-3" id="flight_info_${flight_count}"><hr></div>`);

            // Create date input field
            const $dateInput = $(`
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>GDS Ref. No.</label>
                    <input type="text" class="form-control" name="flights[${flight_count}][gds_no]" placeholder="GDS Ref. Number">
                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Airline Locator</label>
                    <input type="text" class="form-control" name="flights[${flight_count}][airline_locator]" placeholder="Airline Locator">
                </div>
                <div class="col-lg-2 col-md-2 mb-3 d-none">
                    <label>Ticket Number</label>
                    <input type="text" class="form-control" name="flights[${flight_count}][ticket_no]" placeholder="Ticket Number">
                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Flight No</label>
                    <input type="text" class="form-control" name="flights[${flight_count}][flight_number]" placeholder="Flight No">
                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Departure Airport</label>
                    <select class="departure_airports" id="departure_airport_${flight_count}" name="flights[${flight_count}][departure_airport]"></select>
                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Arrival Airport</label>
                    <select class="arrival_airports" id="arrival_airport_${flight_count}" name="flights[${flight_count}][arrival_airport]"></select>
                </div>

                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Departure Date</label>
                    <input type="text" class="form-control" id="departure_date_${flight_count}" name="flights[${flight_count}][departure_date]" value="" placeholder="Departure Date">
                </div>

                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Arrivel Date</label>
                    <input type="text" class="form-control" id="arrival_date_${flight_count}" name="flights[${flight_count}][arrival_date]" value="" placeholder="Arrivel Date">
                </div>

                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Departure Time</label>
                    <input type="time" class="form-control" id="departure_time_${flight_count}" name="flights[${flight_count}][departure_time]" value="" placeholder="Departure Time">
                </div>

                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Arrivel Time</label>
                    <input type="time" class="form-control" id="arrival_time_${flight_count}" name="flights[${flight_count}][arrival_time]" value="" placeholder="Arrivel Time">
                </div>

                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Airline Name</label>
                    <select class="air_line_name" id="air_line_name_${flight_count}" name="flights[${flight_count}][air_line_name]"></select>
                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label>Booking Class</label>
                    <select class="select2 form-control form-control-sm" name="flights[${flight_count}][booking_class]" required="" data-placeholder="Select Booking Class">
                        <option></option>
                        <option>ECONOMY CLASS</option>
                        <option>ECONOMY PLUS</option>
                        <option>PREMIUM ECONOMY</option>
                        <option>BUSINESS CLASS</option>
                        <option>FIRST CLASS</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <label># of Baggage</label>
                    <select class="select2 form-control form-control-sm" name="flights[${flight_count}][number_of_baggage]" required="" data-placeholder="select # of Baggage">
                        <option>0 PC</option>
                        <option>01 PC</option>
                        <option>02 PC</option>
                        <option>03 PC</option>
                        <option>23 KG</option>
                        <option>25 KG</option>
                        <option>30 KG</option>
                        <option>35 KG</option>
                        <option>40 KG</option>
                        <option>45 KG</option>
                        <option>46 KG</option>
                    </select>
                </div>

                <div class="col-lg-1 col-md-1 mb-3">
                        <button type="button"
                            class="btn btn-danger btn-label waves-effect waves-light btn-soft-danger btn-sm clone_row mt-4" onclick="remove_row(flight_info_${flight_count})"><i class="ri-indeterminate-circle-line label-icon align-middle fs-16 me-2"></i>Remove</button>
                </div>

            `);

            $div.append($dateInput);
            // Append the div to the container
            $flightContainer.append($div);
            $("#flight_info_" + flight_count + " select").select2();

            initializeSelect2WithAjax('#departure_airport_' + flight_count, '{{ route('crm.get-airports') }}',
                'Search for airports');
            initializeSelect2WithAjax('#arrival_airport_' + flight_count, '{{ route('crm.get-airports') }}',
                'Search for airports');
            initializeSelect2WithAjax('#air_line_name_' + flight_count, '{{ route('crm.get-airlines') }}',
                'Search for airlines');

            $('#flight_details_count').val(parseInt(flight_count) + +1);

            $(document).ready(function() {
                // Initialize Flatpickr for the start date input
                var startDatePicker = flatpickr("#departure_date_" + flight_count, {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                });
                var startDatePicker = flatpickr("#arrival_date_" + flight_count, {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                });
            });

        };

        function remove_row(row_id, data_type=null) {
            var $row = $(row_id);
            //console.log(row_id, data_type);
            $row.remove();
            if (data_type == 'hotel') {
                calculateHotelPricing();
            }
            if (data_type == 'transport') {
                calculateTransportPricing();
            }
            // Remove the row
            // $row.remove();
        };

        function calculations(id){
            var id = id.split('_')[1];

            $row_net_cost = $("#netCost_"+id).val();
            $row_net_price = $("#netPrice_"+id).val();
            $row_quantity = $("#quantity_"+id).val();

            $row_total_net_cost = $row_net_cost*$row_quantity;
            $('#total_'+id).val($row_total_net_cost);

            $row_total_net_price = $row_net_price*$row_quantity;
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

        function calculate_balance_amount(){
            var total_sales_cost = $('#total_sales_cost').val();
            var deposite_amount = $('#deposite_amount').val();

            var balance_amont = parseFloat(total_sales_cost) - parseFloat(deposite_amount);
            $('#balance_amount').val(balance_amont);

            if (deposite_amount > 0) {
                $('#receipt_voucher').attr('required', true);
                $('#receipt_voucher_required').removeClass('d-none');
            }else{
                $('#receipt_voucher').attr('required', false);
                $('#receipt_voucher_required').addClass('d-none');
            }
        }

        $(function(){
            // Attach change event to the radio buttons
            $('input[name="payment_type"]').change(function () {
                var total_sales_cost = $('#total_sales_cost').val();
                console.log($(this).val(), 'ok');
            // Check the selected value
            if ($(this).val() === '2') {
                // If 'Payment in Installments' is selected, show the div
                $('#deposite_amount').attr('readonly', false)
                $('#deposite_amount').val('0');
                $('#balance_amount').val(total_sales_cost);

                $('.no_of_installments').removeClass('d-none');
            } else {
                // If 'Full Payment' is selected, hide the div
                $('#deposite_amount').attr('readonly', true);
                $('#deposite_amount').val(total_sales_cost);
                $('#balance_amount').val('0');
                $('.no_of_installments').addClass('d-none');
            }
            });
        })


    </script>
@endsection
