self.addEventListener('push', function (event) {
    if (!event.data) return;

    const data = event.data.json();

    const options = {
        body: data.body,
        icon: data.icon || '/images/icon-192.png',
        badge: '/images/badge-72.png',
        vibrate: [200, 100, 200],
        data: { url: (data.data && data.data.url) || data.url || '/' },
        actions: [
            { action: 'open', title: 'Lihat Detail' },
            { action: 'dismiss', title: 'Tutup' },
        ],
        requireInteraction: true,
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    if (event.action === 'dismiss') return;

    event.waitUntil(
        clients.matchAll({ type: 'window' }).then(function (clientList) {
            const url = event.notification.data.url;
            for (const client of clientList) {
                if (client.url === url && 'focus' in client) return client.focus();
            }
            if (clients.openWindow) return clients.openWindow(url);
        })
    );
});