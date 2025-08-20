<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm-hover" data-sidebar-image="none" data-preloader="enable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | HRM - Seven Zones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Umair Mehmood Khan Lodhi (info.devumair@gmail.com)" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico')}}">

    @include('layouts.head-css')

    <!-- custom Css-->
    {{-- <link href="{{ asset('assets') }}/css/custom.min.css" rel="stylesheet" type="text/css" /> --}}
</head>

@section('body')
    @include('layouts.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        {{-- @include('layouts.sidebar') --}}
        @include('layouts.inc.hrm.sidebar')
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

        <!--select2 cdn-->

    <!-- hrm Custom JS-->
    <script src="{{ URL::asset('build/js/hrm-custom.js') }}"></script>

</body>

</html>
