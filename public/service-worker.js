self.addEventListener('push', function(event) {
    console.log("Push Event Received:", event);

    if (event.data) {
        try {
            let data = event.data.json();
            console.log("Push Data:", JSON.stringify(data));

            self.registration.showNotification(data.title || "Default Title");
        } catch (error) {
            console.error("Push Data Parsing Error:", error);
        }
    } else {
        console.warn("No data in push event.");
    }
});




self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(clients.openWindow(event.notification.data.url));
});
