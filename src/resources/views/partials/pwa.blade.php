<!-- PWA Setup -->
{{-- <meta name="theme-color" content="#007bff" /> --}}
<link rel="manifest" href="{{ asset('manifest.json') }}" />

{{-- Service Worker Script --}}
<script type="text/javascript">
    // Initialize the service worker
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
        }, err => {
            console.log('Service Worker registration failed: ', err);
        });
    }

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
