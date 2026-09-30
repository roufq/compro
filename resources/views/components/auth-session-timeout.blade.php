<div
    data-auth-session-timeout
    data-lifetime-milliseconds="{{ (int) config('session.lifetime') * 60 * 1000 }}"
    data-login-url="{{ route('login') }}"
    data-logout-url="{{ route('logout') }}"
    data-csrf-token="{{ csrf_token() }}"
    hidden
></div>

<script>
    (() => {
        if (window.authSessionTimeout) {
            window.authSessionTimeout.refresh();

            return;
        }

        let expiresAt;
        let timeoutId;
        let isLoggingOut = false;

        const settings = () => document.querySelector('[data-auth-session-timeout]');

        const logout = async () => {
            if (isLoggingOut) {
                return;
            }

            isLoggingOut = true;

            const element = settings();

            if (! element) {
                isLoggingOut = false;

                return;
            }

            try {
                await fetch(element.dataset.logoutUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': element.dataset.csrfToken,
                    },
                    body: new URLSearchParams({ _token: element.dataset.csrfToken }),
                });
            } finally {
                window.location.replace(element.dataset.loginUrl);
            }
        };

        const verifyExpiration = () => {
            if (Date.now() >= expiresAt) {
                void logout();
            }
        };

        const refresh = () => {
            const element = settings();

            window.clearTimeout(timeoutId);

            if (! element) {
                return;
            }

            expiresAt = Date.now() + Number(element.dataset.lifetimeMilliseconds);
            timeoutId = window.setTimeout(logout, Number(element.dataset.lifetimeMilliseconds));
        };

        window.authSessionTimeout = { refresh };

        document.addEventListener('visibilitychange', verifyExpiration);
        window.addEventListener('focus', verifyExpiration);
        window.addEventListener('pageshow', verifyExpiration);
        document.addEventListener('livewire:navigated', refresh);
        document.addEventListener('livewire:init', () => {
            Livewire.interceptRequest(({ onSuccess }) => {
                onSuccess(refresh);
            });
        }, { once: true });

        refresh();
    })();
</script>
