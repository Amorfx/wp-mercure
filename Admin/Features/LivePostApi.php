<?php

namespace WpMercure\Admin\Features;

/**
 * Class LivePostApi
 * Add endpoint to WordPress REST API for Gutenberg user
 * TODO change to hook after save post when Gutenberg will be better
 *
 * @package WpMercure\Admin\Features
 * @since 0.1
 */
class LivePostApi {
    public function __construct() {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'wpmercure/v1', '/post/(?P<id>\d+)', array(
                'methods' => 'POST',
                'callback' => [$this, 'apiSendToMercurePost'],
                'args' => array(
                    'id' => array(
                        'validate_callback' => 'is_numeric'
                    ),
                ),
            ) );
        } );
    }

    public function apiSendToMercurePost(\WP_REST_Request $request) {
        $postID = $request->get_param('id');
        do_action('wpmercure_send_message_post_update', $postID);
        return json_encode(['message' => 'ok']);
    }
}
