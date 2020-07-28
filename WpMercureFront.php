<?php
namespace WpMercure;
use WpMercure\Features\LivePost;

/**
 * Enqueue scripts for features (live for example)
 * Class WpMercureFront
 */
class WpMercureFront {
    private $config;

    public function __construct($conf) {
        $this->config = $conf;
        add_action('wpmercure_localize_scripts_configurations', [$this, 'localizeScript'], 10, 2);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    /**
     * Add in javascript the wpmercure variables configuration
     *
     * @param $handle
     * @param array $otherConfigurations
     */
    public function localizeScript($handle, array $otherConfigurations = []) {
        $conf = array_merge(['HUB_PUBLIC_URL' => $this->config['HUB_PUBLIC_URL']], $otherConfigurations);
        wp_localize_script( $handle, 'wpmercure', $conf);
    }

    public function enqueueScripts() {
        // Enqueue script in single
        if (is_single()) {
            if (apply_filters('wpmercure_allow_enqueue_scripts_single', true)) {
                global $post;
                wp_register_script( 'wpmercure_subscribe', plugin_dir_url(__FILE__) . '/assets/js/subscribes.js', array(), '1.0.0', true );
                wp_localize_script( 'wpmercure_subscribe', 'wpmercure', ['HUB_PUBLIC_URL' => $this->config['HUB_PUBLIC_URL'], 'POST_URL' => get_permalink($post)] );
                wp_enqueue_script('wpmercure_subscribe');
            }
        }
    }
}
