// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: "AIzaSyC7IGFix8izt3yVMyq7BJX_WMr960b84Fo",
    authDomain: "assist-store-api.firebaseapp.com",
    databaseURL: "https://assist-store-api-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "assist-store-api",
    storageBucket: "assist-store-api.appspot.com",
    messagingSenderId: "783459354503",
    appId: "1:783459354503:web:8a637830a488057f9c3522",
    measurementId: "G-Y5QH0S473V"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});