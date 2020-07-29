<?php

namespace WpMercure\Features;

use WpMercure\Admin\Features\LivePostAdmin;
use WpMercure\Admin\Features\LivePostApi;
use WpMercure\WpMercure;

class LivePost {
    const FILTER_ID = 'Live';
    const CONFIG_ID = 'LIVE_POST';

    public function init() {
        if (is_admin()) {
            $admin = new LivePostAdmin();
            $admin->init();
        } else {
            $this->initFront();
        }

        // Endpoint API REST
        new LivePostApi();
    }

    public function initFront() {
        add_action('wp_head', [$this, 'addStyleNotification']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function addStyleNotification() {
?>
        <style type="text/css">
            .wpmercure-notification {
                background: white;
                border-radius: 1000px;
                box-shadow: .25rem .25rem 2rem rgba(0,0,0,0.15);
                cursor: pointer;
                left: 50%;
                top: -3rem;
                transform: translate(-50%);
                padding: .75rem 1.5rem;
                position: fixed;
                text-decoration: none;
                z-index: 999;
                transition: top cubic-bezier(.45, 0, .25, 1) .5s;
            }

            .wpmercure-notification.active {
                top: 6rem;
            }
        </style>
<?php
    }

    public function enqueueScripts() {
        $config = WpMercure::getConf();
        $configFeatures = WpMercure::getConf('features');
        // Enqueue script in single
        if (is_single()) {
            if (apply_filters('wpmercure_allow_livepost_single', true)) {
                global $post;
                wp_register_script( 'wpmercure_live-post-subscribe', plugins_url() . '/wp-mercure/assets/js/features/live-post/subscribes.js', array(), '1.0.0', true );
                wp_localize_script( 'wpmercure_live-post-subscribe', 'wpmercure', ['HUB_PUBLIC_URL' => $config['HUB_PUBLIC_URL'], 'SELECTOR_LIVE_POST' => $configFeatures['SELECTOR_LIVE_POST'], 'POST_URL' => get_permalink($post)] );
                wp_enqueue_script('wpmercure_live-post-subscribe');
            }
        }
    }
}
