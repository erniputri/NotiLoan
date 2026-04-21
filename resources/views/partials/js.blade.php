@php
    $usesDashboardChart = request()->routeIs('dashboard');
    $usesSelect2 = request()->routeIs('data.create.step1') || request()->routeIs('pembayaran.create');
@endphp

<!-- plugins:js -->
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->

<!-- inject:js -->
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
<!-- endinject -->

@if ($usesDashboardChart)
    <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
@endif

@if ($usesSelect2)
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
@endif

@stack('scripts')

@include('partials.scripts.app')

@if ($usesDashboardChart)
    @include('partials.scripts.dashboard')
@endif
