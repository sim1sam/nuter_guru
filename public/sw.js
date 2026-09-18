const CACHE_VERSION = 'ecom-pwa-v4';
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const PRECACHE_URLS = [
    '/',
    '/frontend/pwa/icon-192.png',
    '/frontend/pwa/icon-512.png',
    '/frontend/css/fontawesome.min.css',
    '/frontend/webfonts/fa-solid-900.woff2',
    '/frontend/webfonts/fa-brands-400.woff2',
    '/frontend/webfonts/fa-regular-400.woff2',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => cache.addAll(PRECACHE_URLS)).catch(() => undefined)
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((key) => key.startsWith('ecom-pwa-') && key !== STATIC_CACHE)
                    .map((key) => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    if (url.origin !== self.location.origin) {
        return;
    }

    const isHtmlNav = request.mode === 'navigate';
    const isLiveAsset =
        url.pathname.endsWith('.css') ||
        url.pathname.endsWith('.js') ||
        url.pathname.endsWith('.webmanifest') ||
        url.pathname === '/manifest.webmanifest' ||
        url.pathname.startsWith('/locale/');

    if (isHtmlNav || isLiveAsset) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    if (response && response.status === 200 && isHtmlNav) {
                        const copy = response.clone();
                        caches.open(STATIC_CACHE).then((cache) => cache.put(request, copy));
                    }
                    return response;
                })
                .catch(() => caches.match(request).then((cached) => cached || caches.match('/')))
        );
        return;
    }

    if (
        url.pathname.startsWith('/frontend/') ||
        url.pathname.startsWith('/uploads/') ||
        url.pathname.endsWith('.png') ||
        url.pathname.endsWith('.jpg') ||
        url.pathname.endsWith('.jpeg') ||
        url.pathname.endsWith('.webp') ||
        url.pathname.endsWith('.svg') ||
        url.pathname.endsWith('.woff2') ||
        url.pathname.endsWith('.ttf')
    ) {
        event.respondWith(
            caches.match(request).then((cached) => {
                const networkFetch = fetch(request).then((response) => {
                    if (response && response.status === 200) {
                        const copy = response.clone();
                        caches.open(STATIC_CACHE).then((cache) => cache.put(request, copy));
                    }
                    return response;
                });

                // Stale-while-revalidate for fonts/icons
                return cached || networkFetch;
            })
        );
    }
});
