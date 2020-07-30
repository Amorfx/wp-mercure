<?php

namespace WpMercure\Features;

use WpMercure\Admin\Features\LivePostAdmin;
use WpMercure\Admin\Features\LivePostApi;
use WpMercure\WpMercure;

/**
 * Class LivePost
 * Main class for Live Post feature
 * @package WpMercure\Features
 * @since 0.1
 */
class LivePost extends AbstractFeature {
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
        if (!is_single()) {
            return;
        }
        $this->includeNotificationStyle();
    }

    public function enqueueScripts() {
        if (!is_single() || !apply_filters('wpmercure_allow_livepost_single', true)) {
            return;
        }
        global $post;
        $config = WpMercure::getConf();
        $configFeatures = WpMercure::getConf('features');
        wp_register_script( 'wpmercure_live-post-subscribe', plugins_url() . '/wp-mercure/assets/js/features/live-post/subscribes.js', array(), '1.0.0', true );
        wp_localize_script( 'wpmercure_live-post-subscribe', 'wpmercure', ['HUB_PUBLIC_URL' => $config['HUB_PUBLIC_URL'], 'SELECTOR_LIVE_POST' => $configFeatures['SELECTOR_LIVE_POST'], 'POST_URL' => get_permalink($post)] );
        wp_enqueue_script('wpmercure_live-post-subscribe');
    }
}
