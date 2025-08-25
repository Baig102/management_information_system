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
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Main Group <span class="text-danger">*</span></label>
                                <select class="form-select main_group" id="main_group" name="main_group" required>
                                    <option value="">Select Main Group</option>
                                    <option value="Balance Sheet">Balance Sheet</option>
                                    <option value="Profit And Loss Account">Profit And Loss Account</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Sub 1 Group <span class="text-danger">*</span></label>
                                <select class="form-select sub1_group" id="sub1_group" name="sub1_group" required>
                                    <option value="">Select Sub 1 Group</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Sub 2 Group <span class="text-danger">*</span></label>
                                <select class="form-select sub2_group" id="sub2_group" name="sub2_group" required>
                                    <option value="">Select Sub 2 Group</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Detailed Group <span class="text-danger">*</span></label>
                                <select class="form-select detailed_group" id="detailed_group" name="detailed_group"
                                    required>
                                    <option value="">Select Detailed Group</option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-3 hidden" id="vendor_section">
                                <label>Vendor <span class="text-danger">*</span></label>
                                <select class="form-select vendor_id select2-vendor" id="vendor_id" name="vendor_id">
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" data-name="{{ $vendor->name }}">{{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3 hidden" id="customer_section">
                                <label>Business Customer <span class="text-danger">*</span></label>
                                <select class="form-select customer_id select2-customer" id="customer_id"
                                    name="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" data-name="{{ $customer->name }}">
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Account Head <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-bank-card-line fs-5"></i></span>
                                    <input type="text" class="form-control account_head" id="account_head"
                                        name="account_head" placeholder="Enter Account Head" required autocomplete="off">
                                </div>
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
    <script src="{{ URL::asset('build/js/ams-custom.js') }}"></script>
@endsection