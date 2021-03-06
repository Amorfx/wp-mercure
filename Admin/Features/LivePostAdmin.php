<?php
namespace WpMercure\Admin\Features;

use WpMercure\WpMercure;

/**
 * Add box in admin for live post features
 * @package WpMercure\Admin\Features
 * @since 0.1
 */
class LivePostAdmin {
    public function init() {
        // Not gutenberg
        add_action('post_submitbox_misc_actions', [$this, 'addCheckBoxPublish']);

        // For Gutenberg
        add_action('enqueue_block_editor_assets', [$this, 'enqueueEditorScripts']);

        // in save post send to mercure (old editor)
        add_action('save_post', [$this, 'sendToMercure']);
    }

    public function addCheckBoxPublish() {
        if (!apply_filters('wpmercure_admin_show_publish_checkbox', true)) {
            return;
        }
        ?>
        <div class="misc-pub-section misc-pub-push-mercure">
            <label><input type="checkbox" name="publish-mercure"> <?= __('Send post updated notification', 'wpmercure') ?></label>
        </div>
<?php
    }

    public function enqueueEditorScripts() {
        if (!apply_filters('wpmercure_admin_show_publish_checkbox', true)) {
            return;
        }
        wp_enqueue_script(
            'wpmercure-features-livepost',
            plugins_url( 'wp-mercure/assets/js/editor/features/live-post-admin.js'),
            array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-polyfill', 'wp-edit-post', 'wp-data', 'wp-api-fetch')
        );
    }

    /**
     * Fired when post is saved
     * @param $postID
     */
    public function sendToMercure($postID) {
        if (array_key_exists('publish-mercure', $_POST) && $_POST['publish-mercure'] === 'on') {
            do_action('wpmercure_send_message_post_update', $postID);
        }
    }
}
