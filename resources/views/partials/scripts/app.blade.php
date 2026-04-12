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

        document.querySelectorAll('form[data-confirm-message]').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                const message = form.getAttribute('data-confirm-message');

                if (message && !window.confirm(message)) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });
        });

        document.querySelectorAll('form').forEach(function(form) {
            const method = (form.getAttribute('method') || 'GET').toUpperCase();

            if (form.hasAttribute('data-no-loader')) {
                return;
            }

            form.addEventListener('submit', function(event) {
                if (event.defaultPrevented) {
                    return;
                }

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
