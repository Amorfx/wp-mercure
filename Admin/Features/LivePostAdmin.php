<?php
namespace WpMercure\Admin\Features;

use WpMercure\Features\FeaturesInterface;

/**
 * Add box in admin for live post features
 * @package WpMercure\Admin\Features
 */
class LivePostAdmin {
    public function init() {
        // Not gutenberg
        add_action('post_submitbox_misc_actions', [$this, 'addCheckBoxPublish']);

        // For Guttenberg
        add_action('enqueue_block_editor_assets', [$this, 'enqueueEditorScripts']);
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
            array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-polyfill', 'wp-edit-post')
        );
    }
}
