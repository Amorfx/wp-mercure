<?php
namespace WpMercure\Admin\Features;

use WpMercure\WpMercure;

/**
 * Add box in admin for live post features
 * @package WpMercure\Admin\Features
 */
class LiveTaxonomyAdmin {
    public function init() {
        add_action('transition_post_status', [$this, 'sendToMercure'], 10, 3);
    }

    /**
     * Fired when post is saved
     *
     * @param $newStatus
     * @param $oldStatus
     * @param $post
     */
    public function sendToMercure($newStatus, $oldStatus, $post) {
        if ($oldStatus != 'publish' && $newStatus === 'publish') {
            do_action('wpmercure_send_message_tax_update', $post);
        }
    }
}
