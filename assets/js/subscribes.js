const url = new URL(wpmercure.HUB_PUBLIC_URL);
url.searchParams.append('topic', wpmercure.POST_URL);
const eventSource = new EventSource(url);

// The callback will be called every time an update is published
eventSource.onmessage = e => console.log(JSON.parse(e.data)); // do something with the payload

