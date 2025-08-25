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

            .highlight-bg {
                background-color: #f0f8ff;
            }
        </style>
    @endsection

    <form action="{{ route('ams.chartOfAccounts.update') }}" method="post" enctype="multipart/form-data"
        id="chartOfAccountForm">
        @csrf
        <input type="hidden" name="id" value="{{ $account->id }}">
        <p class="text-danger">* Fields Are Required</p>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Chart of Account Information</h4>
                        <div class="flex-shrink-0">
                            <a href="{{ route('ams.chartOfAccounts.index') }}"
                                class="btn btn-soft-info btn-sm float-end btn-label">
                                <i class="ri-database-fill label-icon align-middle fs-16 me-2"></i>
                                Chart of Account List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Main Group -->
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Main Group <span class="text-danger">*</span></label>
                                <select class="form-select main_group" id="main_group" name="main_group" required>
                                    <option value="">Select Main Group</option>
                                    <option value="Balance Sheet" {{ $account->main_group == 'Balance Sheet' ? 'selected' : '' }}>Balance Sheet</option>
                                    <option value="Profit And Loss Account" {{ $account->main_group == 'Profit And Loss Account' ? 'selected' : '' }}>Profit And Loss Account</option>
                                </select>
                            </div>

                            <!-- Sub 1 Group -->
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Sub 1 Group <span class="text-danger">*</span></label>
                                <select class="form-select sub1_group" id="sub1_group" name="sub1_group" required>
                                    <option value="">Select Sub 1 Group</option>
                                </select>
                            </div>

                            <!-- Sub 2 Group -->
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Sub 2 Group <span class="text-danger">*</span></label>
                                <select class="form-select sub2_group" id="sub2_group" name="sub2_group" required>
                                    <option value="">Select Sub 2 Group</option>
                                </select>
                            </div>

                            <!-- Detailed Group -->
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Detailed Group <span class="text-danger">*</span></label>
                                <select class="form-select detailed_group" id="detailed_group" name="detailed_group"
                                    required>
                                    <option value="">Select Detailed Group</option>
                                </select>
                            </div>

                            <!-- Vendor -->
                            <div class="col-lg-2 col-md-6 mb-3 hidden" id="vendor_section">
                                <label>Vendor <span class="text-danger">*</span></label>
                                <select class="form-select vendor_id select2-vendor" id="vendor_id" name="vendor_id">
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ $account->vendor_id == $vendor->id ? 'selected' : '' }} data-name="{{ $vendor->name }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Customer -->
                            <div class="col-lg-2 col-md-6 mb-3 hidden" id="customer_section">
                                <label>Customer <span class="text-danger">*</span></label>
                                <select class="form-select customer_id select2-customer" id="customer_id"
                                    name="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $account->business_customer_id == $customer->id ? 'selected' : '' }} data-name="{{ $customer->name }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Account Head -->
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Account Head <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-bank-card-line fs-5"></i></span>
                                    <input type="text" class="form-control account_head" id="account_head"
                                        value="{{ $account->account_head }}" name="account_head"
                                        placeholder="Enter Account Head" required autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save button -->
        <div class="row mb-3">
            <div class="col-lg-12 text-end">
                <button type="submit" class="btn btn-primary btn-label waves-effect waves-light">
                    <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save Account
                </button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        $(function () {
            $('.select2-vendor').select2({ placeholder: "Select Vendor", allowClear: true });
            $('.select2-customer').select2({ placeholder: "Select Customer", allowClear: true });

            function populateSub1(mainGroup) {
                return new Promise((resolve) => {
                    let options = '<option value="">Select Sub 1 Group</option>';
                    if (mainGroup === 'Balance Sheet') {
                        options += `
                                                            <option value="Equity" {{ $account->sub_group_1 == 'Equity' ? 'selected' : '' }}>Equity</option>
                                                            <option value="Liabilities" {{ $account->sub_group_1 == 'Liabilities' ? 'selected' : '' }}>Liabilities</option>
                                                            <option value="Assets" {{ $account->sub_group_1 == 'Assets' ? 'selected' : '' }}>Assets</option>`;
                    } else if (mainGroup === 'Profit And Loss Account') {
                        options += `
                                                            <option value="Incomes" {{ $account->sub_group_1 == 'Incomes' ? 'selected' : '' }}>Incomes</option>
                                                            <option value="Expenses" {{ $account->sub_group_1 == 'Expenses' ? 'selected' : '' }}>Expenses</option>`;
                    }
                    $('#sub1_group').html(options);
                    $('#sub2_group').html('<option value="">Select Sub 2 Group</option>');
                    $('#detailed_group').html('<option value="">Select Detailed Group</option>');
                    resolve();
                });
            }

            function populateSub2(mainGroup, sub1Group) {
                return new Promise((resolve) => {
                    let options = '<option value="">Select Sub 2 Group</option>';
                    if (mainGroup === 'Balance Sheet') {
                        if (sub1Group === 'Equity') {
                            options += `<option value="Equity">Equity</option>`;
                        } else if (sub1Group === 'Liabilities') {
                            options += `
                                                                <option value="Non Current Liabilities">Non Current Liabilities</option>
                                                                <option value="Current Liabilities">Current Liabilities</option>`;
                        } else if (sub1Group === 'Assets') {
                            options += `
                                                                <option value="Non Current Assets">Non Current Assets</option>
                                                                <option value="Current Assets">Current Assets</option>`;
                        }
                    } else if (mainGroup === 'Profit And Loss Account') {
                        if (sub1Group === 'Incomes') {
                            options += `<option value="Sales">Sales</option>`;
                        } else if (sub1Group === 'Expenses') {
                            options += `
                                                                <option value="Cost of sales">Cost of sales</option>
                                                                <option value="Admin Expenses">Admin Expenses</option>
                                                                <option value="Financial Charges">Financial Charges</option>
                                                                <option value="Selling Expenses">Selling Expenses</option>
                                                                <option value="Other Expenses">Other Expenses</option>
                                                                <option value="Taxation">Taxation</option>`;
                        }
                    }
                    $('#sub2_group').html(options);
                    $('#detailed_group').html('<option value="">Select Detailed Group</option>');
                    resolve();
                });
            }

            function populateDetailed(mainGroup, sub1Group, sub2Group) {
                return new Promise((resolve) => {
                    let options = '<option value="">Select Detailed Group</option>';
                    if (mainGroup === 'Balance Sheet') {
                        if (sub1Group === 'Equity' && sub2Group === 'Equity') {
                            options += `
                                                                <option value="Capital Account">Capital Account</option>
                                                                <option value="Current Account">Current Account</option>`;
                        } else if (sub1Group === 'Liabilities') {
                            if (sub2Group === 'Non Current Liabilities') {
                                options += `
                                                                    <option value="Long term liabilities">Long term liabilities</option>
                                                                    <option value="Long Term Finance Lease">Long Term Finance Lease</option>`;
                            } else if (sub2Group === 'Current Liabilities') {
                                options += `
                                                                    <option value="Short Term Liabilities">Short Term Liabilities</option>
                                                                    <option value="Short Term Finance Lease">Short Term Finance Lease</option>
                                                                    <option value="Trade Creditors">Trade Creditors</option>
                                                                    <option value="Advances And Other Payables">Advances And Other Payables</option>
                                                                    <option value="Taxes Payable">Taxes Payable</option>`;
                            }
                        } else if (sub1Group === 'Assets') {
                            if (sub2Group === 'Non Current Assets') {
                                options += `
                                                                    <option value="Property Plant And Equipment">Property Plant And Equipment</option>
                                                                    <option value="Intangible Assets">Intangible Assets</option>
                                                                    <option value="Long Term Deposits And Pre-Payments">Long Term Deposits And Pre-Payments</option>`;
                            } else if (sub2Group === 'Current Assets') {
                                options += `
                                                                    <option value="Stores And Stock in Hand">Stores And Stock in Hand</option>
                                                                    <option value="Trade Debtors">Trade Debtors</option>
                                                                    <option value="Short Term Advances">Short Term Advances</option>
                                                                    <option value="Other Receivable">Other Receivable</option>
                                                                    <option value="Cash And Bank Balance">Cash And Bank Balance</option>`;
                            }
                        }
                    } else if (mainGroup === 'Profit And Loss Account') {
                        if (sub1Group === 'Incomes' && sub2Group === 'Sales') {
                            options += `<option value="Sales">Sales</option>`;
                        } else if (sub1Group === 'Expenses') {
                            options += `<option value="${sub2Group}">${sub2Group}</option>`;
                        }
                    }
                    $('#detailed_group').html(options);
                    resolve();
                });
            }

            function toggleVendorCustomer(detailedGroup, vendorId, customerId) {
                if (detailedGroup === 'Trade Creditors') {
                    $('#vendor_section').removeClass('hidden');
                    $('#customer_section').addClass('hidden');
                    $('#vendor_id').attr('required', true).val(vendorId).trigger('change');
                    $('#customer_id').removeAttr('required');
                } else if (detailedGroup === 'Trade Debtors') {
                    $('#customer_section').removeClass('hidden');
                    $('#vendor_section').addClass('hidden');
                    $('#customer_id').attr('required', true).val(customerId).trigger('change');
                    $('#vendor_id').removeAttr('required');
                } else {
                    $('#vendor_section, #customer_section').addClass('hidden');
                    $('#vendor_id, #customer_id').removeAttr('required');
                }
            }

            async function populateAccount(account) {
                $('#main_group').val(account.main_group).trigger('change');
                await populateSub1(account.main_group);
                $('#sub1_group').val(account.sub1_group).trigger('change');
                await populateSub2(account.main_group, account.sub1_group);
                $('#sub2_group').val(account.sub2_group).trigger('change');
                await populateDetailed(account.main_group, account.sub1_group, account.sub2_group);
                $('#detailed_group').val(account.detailed_group).trigger('change');
                toggleVendorCustomer(account.detailed_group, account.vendor_id, account.business_customer_id);
            }


            populateAccount({
                main_group: "{{ $account->main_group }}",
                sub1_group: "{{ $account->sub_group_1 }}",
                sub2_group: "{{ $account->sub_group_2 }}",
                detailed_group: "{{ $account->detailed_group }}",
                vendor_id: "{{ $account->vendor_id }}",
                business_customer_id: "{{ $account->business_customer_id }}"
            });

            $('#main_group').change(function () {
                populateSub1($(this).val());
                resetAccountHead();
            });
            $('#sub1_group').change(function () {
                populateSub2($('#main_group').val(), $(this).val());
                resetAccountHead();
            });
            $('#sub2_group').change(function () {
                populateDetailed($('#main_group').val(), $('#sub1_group').val(), $(this).val());
                resetAccountHead();
            });
            $('#detailed_group').change(function () {
                toggleVendorCustomer($(this).val());
                resetAccountHead();
            });

            $('#chartOfAccountForm').submit(function (e) {
                if ($('#vendor_section').is(':visible') && !$('#vendor_id').val()) {
                    e.preventDefault();
                    alert('Please select a vendor');
                }
                if ($('#customer_section').is(':visible') && !$('#customer_id').val()) {
                    e.preventDefault();
                    alert('Please select a customer');
                }
            });
        });

        $('#vendor_id').change(function () {
            $('#account_head').val('');
            $('#account_head').attr('readonly', true);
            let name = $('#vendor_id option:selected').data('name');
            $('#account_head').val(name);

            $('#account_head').addClass('highlight-bg');
        });

        $('#customer_id').change(function () {
            $('#account_head').val('');
            $('#account_head').attr('readonly', true);
            let name = $('#customer_id option:selected').data('name');
            $('#account_head').val(name);

            $('#account_head').addClass('highlight-bg');
        });

        function resetAccountHead() {
            $('#account_head').val('');
            $('#account_head').removeAttr('readonly');
            $('#account_head').removeClass('highlight-bg');
        }
    </script>
@endsection