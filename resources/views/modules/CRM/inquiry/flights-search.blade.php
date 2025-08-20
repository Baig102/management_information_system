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
    .select2.selection{

    }
</style>
    @endsection

    <form action="#" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex p-2">
                        <h4 class="card-title mb-0 flex-grow-1">Flights</h4>

                    </div><!-- end card header -->
                    <div class="card-body bg-light">
                        <p class="text-muted">Find and compare cheap flights</p>
                        <div class="row">
                            <div class="col-lg-2">
                                <label for="trip_type" class="form-label">Trip Type</label>
                                <select class="select2 form-control-sm bg-primary" id="trip_type" name="trip_type" data-placeholder="Select trip type">
                                    <option></option>
                                    <option value="One Way" selected>One Way</option>
                                    <option value="Round Trip">Round Trip</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="number_of_passengers" class="form-label">Number of passenger(s)</label>
                                <select class="select2 form-control-sm" id="number_of_passengers" name="number_of_passengers" data-placeholder="Select Number of passenger(s)">
                                    {{-- <option></option> --}}
                                    @for ($nop = 1; $nop <=10; $nop++)
                                    <option value="{{ $nop; }}">{{ $nop; }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="booking_class" class="form-label">Booking Class</label>
                                <select class="select2 form-control-sm" id="booking_class" name="booking_class" data-placeholder="Select booking class">
                                    {{-- <option></option> --}}
                                    <option value="economy class">Economy Class</option>
                                    <option value="business class">Business Class</option>
                                    <option value="first class">First Class</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="booking_for" class="form-label">Booking For</label>
                                <select class="select2 form-control-sm" id="booking_for" name="booking_for" data-placeholder="Select booking for">
                                    {{-- <option></option> --}}
                                    <option value="booking for customer">Booking for customer</option>
                                    <option value="booking for agent">Booking for agent</option>
                                </select>
                            </div>
                            <div class="col-lg-2 d-none">
                                <label for="agent_company_name" class="form-label">Agent Company Name</label>
                                <input type="text" name="agent_company_name" value="" class="form-control" placeholder="Agent Company Name" id="agent_company_name" autocomplete="off">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-2 col-md-1 mb-3">
                                <label>Departure Airport</label>

                                <!-- Select -->
                                <div class="input-group">
                                    <label class="input-group-text" for="departure_airport">Options</label>
                                    <select class="form-select" id="departure_airport" name="departure_airport"></select>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 mb-3">
                                <span class="btn btn-primary btn-icon waves-effect waves-light w-100 mt-4"><i class="ri-arrow-left-right-line display-6"></i></span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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

            initializeSelect2WithAjax('#departure_airport', '{{ route('crm.get-airports') }}', 'Search for airports');

        })
    </script>
@endsection
