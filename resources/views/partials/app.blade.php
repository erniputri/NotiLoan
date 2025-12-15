<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>NotiLoan</title>
    <!-- plugins:css -->
    @include('partials.css')
    <!-- endinject -->
</head>

<body>

    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        @include('partials._navbar')

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_settings-panel.html -->
            @include('partials._settings-panel')
            <!-- partial -->
            <!-- partial:partials/_sidebar.html -->
            @include('partials._sidebar')
            <!-- partial -->
            @yield('content')

            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    @include('partials.js')
</body>

</html>
