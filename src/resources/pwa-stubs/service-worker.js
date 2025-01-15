const CACHE_NAME = 'offline-pwa-cache-v1';

const filesToCache = [
    '/',
    '/manifest.json',
    '/offline.html',
];

// Cache on install
self.addEventListener('install', event => {
    console.log('[Service Worker] Installing...');
    self.skipWaiting(); // Activate the service worker immediately
    event.waitUntil(preLoad());
});

const preLoad = () => {
    return caches.open(CACHE_NAME).then(cache => {
        console.log('[Service Worker] Pre-caching resources');
        return cache.addAll(filesToCache);
    }).catch(err => {
        console.error('[Service Worker] Pre-cache failed:', err);
    });
};

// Clear old caches on activate
self.addEventListener('activate', event => {
    console.log('[Service Worker] Activating...');
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => !cacheWhitelist.includes(cacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
        .then(() => {
            console.log('[Service Worker] Activated and old caches cleared');
            return self.clients.claim(); // Take control of all clients immediately
        })
    );
});

// Serve from Cache
// self.addEventListener('fetch', event => {
//     event.respondWith(
//         caches.match(event.request).then(response => {
//             // Return cached response or fetch from the network
//             return response || fetch(event.request);
//         }).catch(() => {
//             // Fallback to offline page if network fails
//             return caches.match('offline.html');
//         })
//     );
// });
self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') {
        // Only handle GET requests
        return;
    }

    event.respondWith(
        checkResponse(event.request).then(response => {
            if (response && response.status === 200 && response.type === 'basic') {
                const responseClone = response.clone();
                event.waitUntil(addToCache(event.request, responseClone));
            }
            return response; // Return the original response
        }).catch(err => {
            console.log('Fetch failed; serving cached content:', err);
            return returnFromCache(event.request);
        })
    );
});

const checkResponse = (request) => {
    return new Promise((fulfill, reject) => {
        fetch(request).then(response => {
            fulfill(response);
        }, reject);
    });
};

// const addToCache = (request) => {
//     return caches.open(CACHE_NAME).then(cache => {
//         return fetch(request).then(response => {
//             return cache.put(request, response);
//         });
//     });
// };
const addToCache = (request, response) => {
    return caches.open(CACHE_NAME).then(cache => {
        return cache.put(request, response);
    });
};

const returnFromCache = request => {
    return caches.open(CACHE_NAME).then(cache => {
        return cache.match(request).then(matching => {
            if (!matching || matching.status === 404) {
                return cache.match('offline.html');
            }
            return matching;
        });
    });
};

// Listen for messages from clients (e.g., to trigger updates)
self.addEventListener('message', event => {
    if (event.data === 'skipWaiting') {
        self.skipWaiting();
    }
});
