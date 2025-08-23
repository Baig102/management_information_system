@extends('layouts.master-ams')
@section('title')
    @lang('translation.crm')
@endsection
@section('content')
    @component('components.breadcrumb')
    @slot('li_1')
    Accounts
    @endslot
    @slot('title')
    Chart Of Accounts
    @endslot
    @endcomponent

    @section('css')
        <style>
            .hidden {
                display: none;
            }
        </style>
    @endsection
    <form action="{{ route('ams.chartOfAccounts.save') }}" method="post" enctype="multipart/form-data"
        id="chartOfAccountForm">
        @csrf
        <p @class(['text-danger'])>* Fields Are Required</p>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Chart of Account Information</h4>
                        <div class="flex-shrink-0">
                            <a href="{{ route('ams.chartOfAccounts.index') }}"
                                class="btn btn-soft-info btn-sm float-end btn-label"><i
                                    class="ri-database-fill label-icon align-middle fs-16 me-2"></i> Chart of Account List
                            </a>
                        </div>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label>Account Head <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-bank-card-line fs-5"></i></span>
                                    <input type="text" class="form-control account_head" id="account_head"
                                        value="{{ old('account_head') }}" name="account_head"
                                        placeholder="Enter Account Head" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label>Main Group <span class="text-danger">*</span></label>
                                <select class="form-select main_group" id="main_group" name="main_group" required>
                                    <option value="">Select Main Group</option>
                                    <option value="Balance Sheet">Balance Sheet</option>
                                    <option value="Profit And Loss Account">Profit And Loss Account</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label>Sub 1 Group <span class="text-danger">*</span></label>
                                <select class="form-select sub1_group" id="sub1_group" name="sub1_group" required>
                                    <option value="">Select Sub 1 Group</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label>Sub 2 Group <span class="text-danger">*</span></label>
                                <select class="form-select sub2_group" id="sub2_group" name="sub2_group" required>
                                    <option value="">Select Sub 2 Group</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label>Detailed Group <span class="text-danger">*</span></label>
                                <select class="form-select detailed_group" id="detailed_group" name="detailed_group"
                                    required>
                                    <option value="">Select Detailed Group</option>
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-6 mb-3 hidden" id="vendor_section">
                                <label>Vendor <span class="text-danger">*</span></label>
                                <select class="form-select vendor_id select2-vendor" id="vendor_id" name="vendor_id">
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3 hidden" id="customer_section">
                                <label>Customer <span class="text-danger">*</span></label>
                                <select class="form-select customer_id select2-customer" id="customer_id"
                                    name="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-label waves-effect waves-light"><i
                            class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save Account</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $(function () {
            // Initialize Select2 for vendor and customer dropdowns
            $('.select2-vendor').select2({
                placeholder: "Select Vendor",
                allowClear: true
            });

            $('.select2-customer').select2({
                placeholder: "Select Customer",
                allowClear: true
            });

            // Main Group Change Event
            $('#main_group').change(function () {
                const mainGroup = $(this).val();
                $('#sub1_group').html('<option value="">Select Sub 1 Group</option>');
                $('#sub2_group').html('<option value="">Select Sub 2 Group</option>');
                $('#detailed_group').html('<option value="">Select Detailed Group</option>');

                if (mainGroup === 'Balance Sheet') {
                    $('#sub1_group').append(`
                                <option value="Equity">Equity</option>
                                <option value="Liabilities">Liabilities</option>
                                <option value="Assets">Assets</option>
                            `);
                } else if (mainGroup === 'Profit And Loss Account') {
                    $('#sub1_group').append(`
                                <option value="Incomes">Incomes</option>
                                <option value="Expenses">Expenses</option>
                            `);
                }

                $('#vendor_section, #customer_section').addClass('hidden');
                $('#vendor_id, #customer_id').removeAttr('required');
            });

            // Sub 1 Group Change Event
            $('#sub1_group').change(function () {
                const mainGroup = $('#main_group').val();
                const sub1Group = $(this).val();
                $('#sub2_group').html('<option value="">Select Sub 2 Group</option>');
                $('#detailed_group').html('<option value="">Select Detailed Group</option>');

                if (mainGroup === 'Balance Sheet') {
                    if (sub1Group === 'Equity') {
                        $('#sub2_group').append(`
                                    <option value="Equity">Equity</option>
                                `);
                    } else if (sub1Group === 'Liabilities') {
                        $('#sub2_group').append(`
                                    <option value="Non Current Liabilities">Non Current Liabilities</option>
                                    <option value="Current Liabilities">Current Liabilities</option>
                                `);
                    } else if (sub1Group === 'Assets') {
                        $('#sub2_group').append(`
                                    <option value="Non Current Assets">Non Current Assets</option>
                                    <option value="Current Assets">Current Assets</option>
                                `);
                    }
                } else if (mainGroup === 'Profit And Loss Account') {
                    if (sub1Group === 'Incomes') {
                        $('#sub2_group').append(`
                                    <option value="Sales">Sales</option>
                                `);
                    } else if (sub1Group === 'Expenses') {
                        $('#sub2_group').append(`
                                    <option value="Cost of sales">Cost of sales</option>
                                    <option value="Admin Expenses">Admin Expenses</option>
                                    <option value="Financial Charges">Financial Charges</option>
                                    <option value="Selling Expenses">Selling Expenses</option>
                                    <option value="Other Expenses">Other Expenses</option>
                                    <option value="Taxation">Taxation</option>
                                `);
                    }
                }

                $('#vendor_section, #customer_section').addClass('hidden');
                $('#vendor_id, #customer_id').removeAttr('required');
            });

            // Sub 2 Group Change Event
            $('#sub2_group').change(function () {
                const mainGroup = $('#main_group').val();
                const sub1Group = $('#sub1_group').val();
                const sub2Group = $(this).val();
                $('#detailed_group').html('<option value="">Select Detailed Group</option>');

                if (mainGroup === 'Balance Sheet') {
                    if (sub1Group === 'Equity' && sub2Group === 'Equity') {
                        $('#detailed_group').append(`
                                    <option value="Capital Account">Capital Account</option>
                                    <option value="Current Account">Current Account</option>
                                `);
                    } else if (sub1Group === 'Liabilities') {
                        if (sub2Group === 'Non Current Liabilities') {
                            $('#detailed_group').append(`
                                        <option value="Long term liabilities">Long term liabilities</option>
                                        <option value="Long Term Finance Lease">Long Term Finance Lease</option>
                                    `);
                        } else if (sub2Group === 'Current Liabilities') {
                            $('#detailed_group').append(`
                                        <option value="Short Term Liabilities">Short Term Liabilities</option>
                                        <option value="Short Term Finance Lease">Short Term Finance Lease</option>
                                        <option value="Trade Creditors">Trade Creditors</option>
                                        <option value="Advances And Other Payables">Advances And Other Payables</option>
                                        <option value="Taxes Payable">Taxes Payable</option>
                                    `);
                        }
                    } else if (sub1Group === 'Assets') {
                        if (sub2Group === 'Non Current Assets') {
                            $('#detailed_group').append(`
                                        <option value="Property Plant And Equipment">Property Plant And Equipment</option>
                                        <option value="Intangible Assets">Intangible Assets</option>
                                        <option value="Long Term Deposits And Pre-Payments">Long Term Deposits And Pre-Payments</option>
                                    `);
                        } else if (sub2Group === 'Current Assets') {
                            $('#detailed_group').append(`
                                        <option value="Stores And Stock in Hand">Stores And Stock in Hand</option>
                                        <option value="Trade Debtors">Trade Debtors</option>
                                        <option value="Short Term Advances">Short Term Advances</option>
                                        <option value="Other Receivable">Other Receivable</option>
                                        <option value="Cash And Bank Balance">Cash And Bank Balance</option>
                                    `);
                        }
                    }
                } else if (mainGroup === 'Profit And Loss Account') {
                    if (sub1Group === 'Incomes' && sub2Group === 'Sales') {
                        $('#detailed_group').append(`
                                    <option value="Sales">Sales</option>
                                `);
                    } else if (sub1Group === 'Expenses') {
                        if (sub2Group === 'Cost of sales') {
                            $('#detailed_group').append(`
                                        <option value="Cost of sales">Cost of sales</option>
                                    `);
                        } else if (sub2Group === 'Admin Expenses') {
                            $('#detailed_group').append(`
                                        <option value="Admin Expenses">Admin Expenses</option>
                                    `);
                        } else if (sub2Group === 'Financial Charges') {
                            $('#detailed_group').append(`
                                        <option value="Financial Charges">Financial Charges</option>
                                    `);
                        } else if (sub2Group === 'Selling Expenses') {
                            $('#detailed_group').append(`
                                        <option value="Selling Expenses">Selling Expenses</option>
                                    `);
                        } else if (sub2Group === 'Other Expenses') {
                            $('#detailed_group').append(`
                                        <option value="Other Expenses">Other Expenses</option>
                                    `);
                        } else if (sub2Group === 'Taxation') {
                            $('#detailed_group').append(`
                                        <option value="Taxation">Taxation</option>
                                    `);
                        }
                    }
                }

                $('#vendor_section, #customer_section').addClass('hidden');
                $('#vendor_id, #customer_id').removeAttr('required');
            });

            // Detailed Group Change Event
            $('#detailed_group').change(function () {
                const detailedGroup = $(this).val();

                if (detailedGroup === 'Trade Creditors') {
                    $('#vendor_section').removeClass('hidden');
                    $('#customer_section').addClass('hidden');
                    $('#vendor_id').attr('required', 'required');
                    $('#customer_id').removeAttr('required');

                    // Initialize Select2 when vendor section is shown
                    setTimeout(function () {
                        $('.select2-vendor').select2({
                            placeholder: "Select Vendor",
                            allowClear: true
                        });
                    }, 100);
                } else if (detailedGroup === 'Trade Debtors') {
                    $('#customer_section').removeClass('hidden');
                    $('#vendor_section').addClass('hidden');
                    $('#customer_id').attr('required', 'required');
                    $('#vendor_id').removeAttr('required');

                    setTimeout(function () {
                        $('.select2-customer').select2({
                            placeholder: "Select Customer",
                            allowClear: true
                        });
                    }, 100);
                } else {
                    $('#vendor_section, #customer_section').addClass('hidden');
                    $('#vendor_id, #customer_id').removeAttr('required');
                }
            });

            $('#chartOfAccountForm').submit(function (e) {
                if ($('#vendor_section').is(':visible') && !$('#vendor_id').val()) {
                    e.preventDefault();
                    alert('Please select a vendor');
                    return false;
                }

                if ($('#customer_section').is(':visible') && !$('#customer_id').val()) {
                    e.preventDefault();
                    alert('Please select a customer');
                    return false;
                }
            });
        });
    </script>
@endsection