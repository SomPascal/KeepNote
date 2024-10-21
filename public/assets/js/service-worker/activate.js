if ('serviceWorker' in navigator)
{
    navigator.serviceWorker.register("/assets/service-worker/service-worker.js")
}
else console.error("wrong");