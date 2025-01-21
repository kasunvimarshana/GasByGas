<!-- PWA Setup -->
{{-- <meta name="theme-color" content="#007bff" /> --}}
<link rel="manifest" href="{{ asset('manifest.json') }}" />
<meta name="vapid-public-key" content="{{ config('webpush.vapid.public_key') }}" />

{{-- Service Worker Script --}}
<script type="text/javascript">
    // Initialize the service worker
    window.addEventListener('load', () => {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset('service-worker.js') }}', {})
                .then(registration => {
                    // Registration was successful
                    console.log('Service Worker has been registered for scope: ', registration.scope);

                    // // Listen for updates
                    // registration.addEventListener('updatefound', () => {
                    //     const newWorker = registration.installing;
                    //     newWorker.addEventListener('statechange', () => {
                    //         if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                    //             // Notify users about the new version
                    //             console.log('New version available. Refresh the page.');
                    //         }
                    //     });
                    // });

                    subscribeToPushNotifications();
                }, err => {
                    console.log('Service Worker registration failed: ', err);
                });
        }
    });

    window.addEventListener('beforeinstallprompt', (e) => {
        console.log('beforeinstallprompt event:', e);
        e.preventDefault();
        let deferredPrompt = e;
        const installButton = document.getElementById('install-button');
        installButton.style.display = 'block';

        installButton.addEventListener('click', () => {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('User accepted the install prompt');
                } else {
                    console.log('User dismissed the install prompt');
                }
                deferredPrompt = null;
            });
        });
    });

    window.addEventListener('appinstalled', () => {
        console.log('PWA installed');
    });
</script>

<script type="text/javascript">
    const getPWADisplayMode = () => {
        if (document.referrer.startsWith('android-app://')) {
            return 'twa';
        }
        if (window.matchMedia('(display-mode: browser)').matches) {
            return 'browser';
        }
        if (window.matchMedia('(display-mode: standalone)').matches) {
            return 'standalone';
        }
        if (window.matchMedia('(display-mode: minimal-ui)').matches) {
            return 'minimal-ui';
        }
        if (window.matchMedia('(display-mode: fullscreen)').matches) {
            return 'fullscreen';
        }
        if (window.matchMedia('(display-mode: window-controls-overlay)').matches) {
            return 'window-controls-overlay';
        }

        return 'unknown';
    }

    window.addEventListener('DOMContentLoaded', () => {
        // Log launch display mode to analytics
        console.log('DISPLAY_MODE_LAUNCH:', getPWADisplayMode());
    });
</script>

<script type="text/javascript">
    function subscribeToPushNotifications() {
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            const vapidPublicKey = document.querySelector('meta[name="vapid-public-key"]').getAttribute('content');
            navigator.serviceWorker.ready.then((registration) => {
                registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
                }).then((subscription) => {
                    console.log('User is subscribed:', subscription);
                    handleSubscription(subscription);
                }).catch((error) => {
                    console.error('Subscription Error:', error);
                });
            });
        }
    }

    function unsubscribePushNotifications() {
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.ready.then((registration) => {
                registration.pushManager.getSubscription().then((subscription) => {
                    if (subscription) {
                        // Unsubscribe from the push manager
                        subscription.unsubscribe().then(() => {
                            handleUnSubscription(subscription);
                        });
                    }
                    console.log('User is unsubscribed:', subscription);
                }).catch((error) => {
                    console.error('Subscription Error:', error);
                });
            });
        }
    }

    // Helper function to convert the VAPID public key to a Uint8Array
    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = atob(base64);
        /*
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        */
        const outputArray = Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
        return outputArray;
    }

    function handleSubscription(subscription) {
        fetch('{!! route('push-subscriptions.store') !!}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(subscription),
        })
        .then((response) => response.json())
        .then((data) => {
            console.log('Subscription successful:', data);
        })
        .catch((error) => {
            console.error('Error saving subscription:', error);
        });
    }

    function handleUnSubscription(subscription) {
        fetch('{!! route('push-subscriptions.destroy') !!}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(subscription),
        })
        .then((response) => response.json())
        .then((data) => {
            console.log('UnSubscription successful:', data);
        })
        .catch((error) => {
            console.error('Error deleting subscription:', error);
        });
    }
</script>
