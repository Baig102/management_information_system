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
            Inquiry
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
    <form action="{{ route('crm.save-inquiry') }}" method="post" enctype="multipart/form-data">
        @csrf
        <p @class(['text-danger'])>* Fields Are Required</p>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Inquiry Information</h4>
                        <div class="flex-shrink-0">
                            <a href="{{ route('crm.inquiry-list') }}" class="btn btn-soft-info btn-sm float-end btn-label"><i class="ri-database-fill label-icon align-middle fs-16 me-2"></i> Inquiries List </a>
                        </div>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <label for="company" class="form-label">Select Company<span class="text-danger"> *</span></label>
                                <select class="select2 form-control-sm" id="company" name="company" data-placeholder="Select Company" required>
                                    <option></option>
                                    @foreach ($assignedCompanies as $assiCompany)
                                    <option value="{{ $assiCompany->id.'__'.$assiCompany->name }}">{{ $assiCompany->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Inquiry Date & Time</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                    <input type="text" name="inquiry_date_time" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date-time" data-provider="flatpickr" id="inquiry_date_time" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-shield-user-line fs-5"></i></span>
                                    <input type="text" class="form-control full_name" id="full_name" name="full_name" value="" placeholder="Enter full name" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Email</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-mail-line fs-5"></i></span>
                                    <input type="email" class="form-control email" id="email" name="email" value="" placeholder="Enter email" step="0.01" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Phone #</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-phone-line fs-5"></i></span>
                                    <input type="text" class="form-control phone_number" id="phone_number" name="phone_number" value="" placeholder="Enter phone number" required autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Departure From</label>
                                <div class="input-group">
                                    <select class="select2 departure_from" id="departure_from" name="departure_from"></select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Destination</label>
                                <div class="input-group">
                                    <select class="select2 destination" id="destination" name="destination"></select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Departure Date</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                    <input type="text" name="departure_date" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="departure_date" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Return Date</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-time-line fs-5"></i></span>
                                    <input type="text" name="return_date" value="" placeholder="DD-MM-YYYY" class="form-control flatpickr-date" data-provider="flatpickr" id="return_date" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Best Time To Call</label>
                                <div class="input-group">
                                    <span class="input-group-text px-2 py-1"><i class="ri-timer-flash-line fs-5"></i></span>
                                    <input type="text" class="form-control best_time_to_call" id="best_time_to_call" name="best_time_to_call" value="Any Time" placeholder="Best Time To Call" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label>Source</label>
                                <select name="source" class="form-select mb-3" aria-label="Source">
                                    <option value="Facebook">Facebook</option>
                                    <option value="Website">Website</option>
                                    <option value="Via Phone Call" selected>Via Phone Call</option>
                                    <option value="Live Chat">Live Chat</option>
                                    <option value="Referral Client">Referral Client</option>
                                    <option value="Existing Client">Existing Client</option>
                                    <option value="From Messenger">From Messenger</option>
                                    <option value="From Social Media Comments">From Social Media Comments</option>
                                    <option value="Walk In Office">Walk In Office</option>

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
                <button type="submit" class="btn btn-primary btn-label waves-effect waves-light"><i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Save Inquiry</button>
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

            // Initialize Select2 when the modal is shown

        initializeSelect2WithAjax('#departure_from', '{{ route('crm.get-airports') }}', 'Search for departure from');
        initializeSelect2WithAjax('#destination', '{{ route('crm.get-airports') }}', 'Search for destination');

        })


        $(".flatpickr-date-time").flatpickr({
            altInput: true,
            //altFormat: "F j, Y",
            //dateFormat: "Y-m-d",
            dateFormat: "Y-m-d H:i:s",
            //minDate: "today",
            maxDate: "today",
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
