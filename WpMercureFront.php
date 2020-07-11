<?php
namespace WpMercure;
use WpMercure\Functionnalities\Live;

/**
 * Enqueue scripts for features (live for example)
 * Class WpMercureFront
 */
class WpMercureFront {
    private $config;
    private $functionnalities;

    public function __construct($conf) {
        $this->config = $conf;
        $this->functionnalities = [Live::class];
        $this->initFunctionnalities();
    }

    public function initFunctionnalities() {
        foreach ($this->functionnalities as $aClass) {
            if (apply_filters('wpmercure_allow_' . $aClass::FILTER_ID, true)) {
                new $aClass();
            }
        }
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
