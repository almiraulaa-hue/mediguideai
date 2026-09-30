// resources/js/push-notification.js
async function initPushNotification() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        console.warn('Push notification tidak didukung browser ini.');
        return;
    }

    const registration = await navigator.serviceWorker.register('/sw.js');
    const permission = await Notification.requestPermission();

    if (permission !== 'granted') {
        console.warn('Izin notifikasi ditolak user.');
        return;
    }

    const vapidPublicKey = document.querySelector('meta[name="vapid-key"]').content;

    const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
    });

    await fetch('/push/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(subscription),
    });
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    return Uint8Array.from([...rawData].map((c) => c.charCodeAt(0)));
}

document.getElementById('enable-notif-btn')?.addEventListener('click', initPushNotification);