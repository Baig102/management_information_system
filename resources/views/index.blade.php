{{-- @extends('layouts.master') --}}
@extends('layouts.master-modules')
@section('title')
@lang('translation.dashboards')
@endsection
@section('css')
{{-- <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css" /> --}}
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">Welcome {{ Auth::user()->name }}!</h4>
                            <p class="text-muted mb-0">List of Modules assiged to you.</p>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row">
                <div id="teamlist">
                    <div class="team-list grid-view-filter row" id="team-member-list">
                        @foreach ($modules as $modul)

                        <div class="col-xl-3 col-md-6">
                            <a href="{{ route($modul->module_link . '.index') }}">
                                <div class="card team-box">
                                    <div class="team-cover"> <img src="{{ asset('build/images/small/img-1.jpg') }}" alt="" class="img-fluid"> </div>
                                    <div class="card-body p-4">
                                        <div class="row align-items-center team-row">
                                            <div class="col-lg-4 col">
                                                <div class="team-profile-img">
                                                    <div class="avatar-lg img-thumbnail rounded-circle flex-shrink-0">{!! $modul->icon !!}{{-- <img src="{{URL::asset('build/images/users/avatar-2.jpg')}}" alt="" class="member-img img-fluid d-block rounded-circle"> --}}</div>
                                                    <div class="team-content">

                                                        <h5 class="fs-16 mb-1">{{ $modul->name }}</h5>

                                                        {{-- <p class="text-muted member-designation mb-0">Team Leader &amp; HR </p> --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col">
                                                <div class="text-end"> <a href="{{ route($modul->module_link . '.index') }}" class="btn btn-light view-btn">View Module</a> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div> <!-- end row-->
        </div> <!-- end .h-100-->
    </div> <!-- end col -->
</div>
@endsection
@section('script')
{{-- <!-- apexcharts -->
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js')}}"></script>
<!-- dashboard init -->
<script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script> --}}
@endsection
