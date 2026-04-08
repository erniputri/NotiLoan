<!-- plugins:js -->
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/dataTables.select.min.js') }}"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<!-- endinject -->

<!-- Custom js for this page -->
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/Chart.roundedBarCharts.js') }}"></script>
<!-- End custom js for this page -->

<!-- JQUERY-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SELECT2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingOverlay = document.getElementById('appLoadingOverlay');

        function showLoadingOverlay() {
            if (!loadingOverlay) {
                return;
            }

            loadingOverlay.classList.add('is-visible');
            loadingOverlay.setAttribute('aria-hidden', 'false');
        }

        function disableSubmitButtons(form) {
            form.querySelectorAll('button[type="submit"]').forEach(function(button) {
                button.disabled = true;
            });
        }

        document.querySelectorAll('[data-feedback-close]').forEach(function(button) {
            button.addEventListener('click', function() {
                const toast = button.closest('[data-feedback-toast]');
                if (toast) {
                    toast.remove();
                }
            });
        });

        document.querySelectorAll('[data-feedback-toast]').forEach(function(toast, index) {
            window.setTimeout(function() {
                if (toast && toast.isConnected) {
                    toast.remove();
                }
            }, 4500 + (index * 400));
        });

        document.querySelectorAll('form').forEach(function(form) {
            const method = (form.getAttribute('method') || 'GET').toUpperCase();

            if (form.hasAttribute('data-no-loader')) {
                return;
            }

            form.addEventListener('submit', function() {
                showLoadingOverlay();

                if (method !== 'GET') {
                    disableSubmitButtons(form);
                }
            });
        });

        document.querySelectorAll('a[href]').forEach(function(link) {
            link.addEventListener('click', function(event) {
                const href = link.getAttribute('href');

                if (!href ||
                    href.startsWith('#') ||
                    href.startsWith('javascript:') ||
                    link.hasAttribute('download') ||
                    link.getAttribute('target') === '_blank' ||
                    link.hasAttribute('data-no-loader') ||
                    link.hasAttribute('data-toggle') ||
                    event.ctrlKey ||
                    event.metaKey ||
                    event.shiftKey ||
                    event.altKey) {
                    return;
                }

                const currentUrl = new URL(window.location.href);
                const targetUrl = new URL(link.href, window.location.href);

                if (targetUrl.origin !== currentUrl.origin) {
                    return;
                }

                if (targetUrl.href === currentUrl.href) {
                    return;
                }

                showLoadingOverlay();
            });
        });

        window.addEventListener('pageshow', function() {
            if (loadingOverlay) {
                loadingOverlay.classList.remove('is-visible');
                loadingOverlay.setAttribute('aria-hidden', 'true');
            }
        });
    });
</script>
