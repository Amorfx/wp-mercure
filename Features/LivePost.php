<?php

namespace WpMercure\Features;

use WpMercure\Admin\Features\LivePostAdmin;

class LivePost implements FeaturesInterface {
    const FILTER_ID = 'Live';
    const CONFIG_ID = 'LIVE_POST';

    public function init() {
        if (is_admin()) {
            $admin = new LivePostAdmin();
            $admin->init();
        } else {
            $this->initFront();
        }
    }

    public function initFront() {

    }
}
