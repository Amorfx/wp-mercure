const url = new URL(wpmercure.HUB_PUBLIC_URL);
url.searchParams.append('topic', wpmercure.POST_URL);
const eventSource = new EventSource(url);

const NotificationBubble = {
  currentNotification: null,
  data: null,
  currentSelectorUpdatePost: wpmercure.SELECTOR_LIVE_POST,

  createNotification: function (data) {
    this.removeAllNotifications();
    this.data = data;
    const notification = document.createElement('div');
    notification.className = 'wpmercure-notification';
    const notificationText = document.createTextNode(data.message);
    notification.appendChild(notificationText);
    notification.addEventListener('click', function(event) {
      NotificationBubble.onClick();
    });
    document.body.insertBefore(notification, document.body.firstChild);
    this.currentNotification = notification;
    setTimeout(function() {
      notification.className = notification.className + ' active';
    }, 100);
  },

  removeAllNotifications: function() {
    document.querySelectorAll('.wpmercure-notification').forEach(function(element) {
      element.remove();
    });
  },

  onClick: function() {
    this.removeAllNotifications();
    this.replacePostContent()
    this.currentNotification = null;
    this.data = null;
  },

  replacePostContent: function () {
    document.querySelector(this.currentSelectorUpdatePost).innerHTML = this.data.post_content;
  }
};

// The callback will be called every time an update is published (or click in send notification button)
eventSource.onmessage = e => {
  const data = JSON.parse(e.data);
  NotificationBubble.createNotification(data);
}

