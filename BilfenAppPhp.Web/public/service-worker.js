self.addEventListener("push", function (event) {
    debugger;
    const data = event.data.json();
    const options = {
        body: data.body,
        icon: "/logo.jpg",
    };
    event.waitUntil(self.registration.showNotification(data.title, options));
});
