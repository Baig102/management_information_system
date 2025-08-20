  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
{{-- <script src="{{ URL::asset('build/js/plugins.js') }}"></script> --}}

<!-- Sweet Alerts js -->
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Sweet alert init js-->
<script src="{{ URL::asset('build/js/pages/sweetalerts.init.js') }}"></script>

{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
<script src="{{ URL::asset('build/js/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/select2.init.js')}}"></script>

<!-- cleave.js -->
<script src="{{ URL::asset('build/libs/cleave.js/cleave.min.js') }}"></script>


<!-- choices.js -->
<script type='text/javascript' src='{{ URL::asset('build/libs/choices.js/public/assets/scripts/choices.min.js') }}'></script>

<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/toastify-js'></script>
{{-- <script type='text/javascript' src="{{ URL::asset('build/libs/choices.js/public/assets/scripts/choices.min.js')}}"></script> --}}
<script type='text/javascript' src="{{ URL::asset('build/libs/flatpickr/flatpickr.min.js') }}"></script>
<!-- ckeditor -->
<script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js')}}"></script>
{{-- <script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js')}}"></script> --}}
{{-- <script src="https://cdn.ckeditor.com/4.24.0-lts/standard/ckeditor.js"></script> --}}

<!-- init js -->
<script src="{{ URL::asset('build/js/pages/form-editor.init.js')}}"></script>
<script src="{{ URL::asset('build/js/app.js')}}"></script>

@yield('script')
@yield('script-bottom')
