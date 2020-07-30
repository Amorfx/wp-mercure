<?php

namespace WpMercure\Features;

use WpMercure\Admin\Features\LivePostAdmin;
use WpMercure\Admin\Features\LivePostApi;
use WpMercure\Admin\Features\LiveTaxonomyAdmin;
use WpMercure\WpMercure;

class LiveTaxonomy extends AbstractFeature {
    const FILTER_ID = 'LiveTaxonomy';
    const CONFIG_ID = 'LIVE_TAXONOMIES';

    public function init() {
        if (is_admin()) {
            $admin = new LiveTaxonomyAdmin();
            $admin->init();
        } else {
            $this->initFront();
        }
    }

    public function initFront() {
        add_action('wp_head', [$this, 'addStyleNotification']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function addStyleNotification() {
        if (!is_tax()) {
            return;
        }
        $this->includeNotificationStyle();
    }

    public function enqueueScripts() {
        if (!is_tax()) {
            return;
        }

        $config = WpMercure::getConf();
        $configFeatures = WpMercure::getConf('features');
        // Enqueue script in single
        if (is_single()) {
            if (apply_filters('wpmercure_allow_livepost_taxonomy', true)) {
                /** @var \WP_Term $term */
                $term = get_queried_object();
                wp_register_script( 'wpmercure_live-tax-subscribe', plugins_url() . '/wp-mercure/assets/js/features/live-taxonomy/subscribes.js', array(), '1.0.0', true );
                wp_localize_script( 'wpmercure_live-tax-subscribe', 'wpmercure', ['HUB_PUBLIC_URL' => $config['HUB_PUBLIC_URL'], 'SELECTOR_LIVE_POST' => $configFeatures['SELECTOR_LIVE_POST'], 'TERM_URL' => get_term_link($term)] );
                wp_enqueue_script('wpmercure_live-tax-subscribe');
            }
        }
    }
}
