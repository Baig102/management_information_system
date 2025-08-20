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
            Business Customer
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
    <form action="{{ route('ams.businessCustomer.save') }}" method="post" enctype="multipart/form-data">
        @csrf
        <p @class(['text-danger'])>* Fields Are Required</p>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Business Customer Information</h4>
                        <div class="flex-shrink-0">
                            <a href="{{ route('ams.businessCustomer.index') }}" class="btn btn-soft-info btn-sm float-end btn-label"><i class="ri-database-fill label-icon align-middle fs-16 me-2"></i> Business Customer List </a>
                        </div>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Business Customer Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-shield-user-line fs-5"></i></span>
                                    <input type="text" class="form-control name" id="name" name="name" placeholder="Enter Business Customer Name" required autocomplete="off" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Official Email</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-mail-line fs-5"></i></span>
                                    <input type="email" class="form-control email" id="email" name="email" placeholder="Enter email" autocomplete="off" required value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Official Phone #</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-phone-line fs-5"></i></span>
                                    <input type="text" class="form-control phone" id="phone" name="phone" placeholder="Enter phone number" autocomplete="off" max="25" value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Website</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-link fs-5"></i></span>
                                    <input type="text" class="form-control website" id="website" name="website" placeholder="Enter website" autocomplete="off" value="{{ old('website') }}">
                                </div>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label>Address</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-map-pin-line fs-5"></i></span>
                                    <input type="text" class="form-control address" id="address" name="address" placeholder="Enter address" autocomplete="off" value="{{ old('address') }}">
                                </div>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="" class="form-label">Is Active</label>
                                <div class="form-check form-switch form-switch-custom form-switch-primary">
                                    <input class="form-check-input" type="checkbox" name="is_active" role="switch" id="isActive" {{ old('is_active', 'on') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Yes</label>
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
                <button type="submit" class="btn btn-primary btn-label waves-effect waves-light"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save Business Customer</button>
            </div>
            </div>
        </div>

    </form>

@endsection
@section('filters-offcanvas')
    <!-- Filters -->
    {{-- @include('modules.CRM.booking.inc.filters-offcanvas') --}}

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


        })


        $(".flatpickr-date-time").flatpickr({
            altInput: true,
            //altFormat: "F j, Y",
            //dateFormat: "Y-m-d",
            dateFormat: "Y-m-d H:i:s",
            //minDate: "today",
            //defaultDate: "today",
            enableTime: true,
            time_24hr: true,
        });

        $(".flatpickr-date").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            //minDate: "today",
            //defaultDate: "today",
        });

    </script>
@endsection
