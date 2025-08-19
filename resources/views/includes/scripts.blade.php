@stack('top_scripts')
<script src="{{ asset('assets/vendor/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/jquery/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert/sweetalert2.min.js') }}"></script>
@stack('scripts_libs')
<script src="{{ asset('assets/vendor/libs/toggle-master/bootstrap-toggle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatable/datatables.jq.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/ckeditor/ckeditor.js') }}""></script>
<script src="{{ asset('assets/vendor/admin/js/application.js') }}"></script>
@toastr_render
@stack('scripts')
