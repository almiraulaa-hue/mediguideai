async function initPushNotification() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        alert('Browser ini tidak mendukung push notification.');
        return;
    }

    try {
        const registration = await navigator.serviceWorker.register('/sw.js');
        await navigator.serviceWorker.ready;

        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            alert('Izin notifikasi ditolak. Aktifkan lewat pengaturan situs di browser.');
            return;
        }

        const vapidPublicKey = document.querySelector('meta[name="vapid-key"]').content;

        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
        });

        const response = await fetch('/push/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(subscription),
        });

        if (response.ok) {
            alert('Notifikasi berhasil diaktifkan!');
        } else {
            alert('Gagal menyimpan subscription (status ' + response.status + ').');
        }
    } catch (error) {
        console.error('Push notification error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    return Uint8Array.from([...rawData].map((c) => c.charCodeAt(0)));
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('enable-notif-btn')?.addEventListener('click', initPushNotification);
});