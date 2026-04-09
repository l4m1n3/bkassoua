// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// // Fonction pour lire le CSRF token même si appelée tôt
// function getCsrfToken() {
//     return document.querySelector('meta[name="csrf-token"]')?.content
//         ?? document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1]
//         ?? '';
// }

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: 'realtime.ably.io',
//     wsPort: 443,
//     wssPort: 443,
//     forceTLS: true,
//     encrypted: true,
//     disableStats: true,
//     cluster: 'eu',
//     enabledTransports: ['ws', 'wss'],
//     auth: {
//         headers: {
//             'X-CSRF-TOKEN': getCsrfToken(),
//         },
//     },
// });
import * as Ably from 'ably';
import Echo from '@ably/laravel-echo';

window.Ably = Ably;

window.Echo = new Echo({
    broadcaster: 'ably',
    key: import.meta.env.VITE_ABLY_KEY,
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        },
    },
});

console.log('✅ Echo + Ably natif initialisé');