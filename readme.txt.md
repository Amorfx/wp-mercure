=== WP Mercure ===
Contributors: cdecou
Tags: mercure,real-time,realtime,post,update,notification
Donate link: https://www.tipeeestream.com/amorfx/donation
Requires at least: 4.9
Tested up to: 5.4.2
Requires PHP: 7.1.3
Stable tag: 0.1
License: GPL

Add WordPress integration of Mercure protocol and add realtime post modification.

== Description ==
Used for developer or non developer with a Mercure server configured.

The plugin permit to send notification for the current visitor of your site.
One feature is to send notification of a current reader of your post to notify that there is a new version of the post content. The user click to the notification and the post content updated in real time.


== Installation ==
- Install Mercure in your server and start it (see [Install section for Mercure](https://mercure.rocks/docs/hub/install))
- Search for \"WP Mercure\" under \"Plugins → Add New\" in your WordPress dashboard.
- Configure the plugin with the plugin admin menu

== Frequently Asked Questions ==
## How to disable live post ?
Click to featured menu admin and disable the functionality \"Live post\"

## How to disable live post for specific posts
Use wpmercure_allow_livepost_single filter

## How can i use my own style of notification ?
Disable the default notification with the filter wpmercure_include_notification_style

## The post not changed after click to the notification
Check if the css selector configured in the admin menu is the correct for your theme.

== Screenshots ==
1. View of one notification sent
