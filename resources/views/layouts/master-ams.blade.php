<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light"
    data-sidebar="dark" data-sidebar-size="sm-hover" data-sidebar-image="none" data-preloader="enable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Seven Zones - AMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" /> -->
    <meta content="Umair Mehmood Khan Lodhi (info.devumair@gmail.com)" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico')}}">
    @include('layouts.head-css')

    @yield('css')

    <style>
        /* Loader CSS */
        .flight-loader {
            position: fixed;
            z-index: 9999;
            height: 100%;
            width: 100%;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.8);
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .flight-loader .spinner {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 2rem;
            color: #007bff;
        }

        .flight-loader .spinner span {
            margin-top: 0.5rem;
            font-size: 1.5rem;
        }
    </style>
</head>

@section('body')
@include('layouts.body')
@show
<!-- Loader HTML -->
<div id="flightLoader" class="flight-loader" style="display:none;">
    <div class="spinner">
        ✈️
        <span>Loading...</span>
    </div>
</div>

<!-- Begin page -->
<div id="layout-wrapper">
    @include('layouts.topbar')
    {{-- @include('layouts.sidebar') --}}
    @include('layouts.inc.ams.sidebar')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('layouts.gen.flash-message')
                @yield('content')
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        @include('layouts.footer')
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->

{{-- @include('layouts.customizer') --}}

<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->

<!--Offcanvas Filters-->
@yield('filters-offcanvas')
<!-- END -->
<!-- Theme Settings -->


@include('layouts.gen.full-screen-modal')
@include('layouts.gen.confirm-modal')
@include('layouts.gen.extra-large-modal')
@include('layouts.gen.large-modal')

{{-- @include('layouts.customizer') --}}


<!-- JAVASCRIPT -->

@include('layouts.vendor-scripts')

@include('layouts.dataTables-scripts')

<!-- CRM Custom JS-->
<script src="{{ URL::asset('build/js/ams-custom.js') }}"></script>

</body>

</html>