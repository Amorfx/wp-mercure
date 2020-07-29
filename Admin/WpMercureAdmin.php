<?php

namespace WpMercure\Admin;

use WpMercure\WpMercure;

class WpMercureAdmin {
    public function __construct() {
        add_action( 'admin_menu', [$this, 'addMenuPage']);
    }

    public function addMenuPage() {
        $mainPage = add_menu_page(
            __( 'Mercure', 'wpmercure' ),
            'Mercure',
            'manage_options',
            'wpmercure-admin',
            [$this, 'renderMenu'],
            'dashicons-email-alt2'
        );

        add_submenu_page(
            'wpmercure-admin',
            __('Features', 'wpmercure'),
            __('Features', 'wpmercure'),
            'manage_options',
            'wpmercure-features',
            [$this, 'renderMenuFunctions']
        );
    }

    public function renderMenu() {
        $save = false;
        if (array_key_exists('page', $_POST) && wp_verify_nonce($_POST['_wpnonce'], 'wpmercure-admin')) {
            $hubUrl = '';
            if (!empty($_POST['hub-url-back'])) {
                $hubUrl = htmlentities($_POST['hub-url-back']);
            }

            if (!empty($_POST['hub-url-front'])) {
                $hubFront = htmlentities($_POST['hub-url-front']);
            } else {
                $hubFront = $hubUrl;
            }

            $jwt = '';
            if (!empty($_POST['jwt-token'])) {
                $jwt = htmlentities($_POST['jwt-token']);
            }

            // save conf
            $config = array(
                'HUB_URL' => $hubUrl,
                'HUB_PUBLIC_URL' => $hubFront,
                'JWT' => $jwt
            );
            $save = WpMercure::saveConfig('configuration', $config);
        }
        $config = WpMercure::getConf();

        if ($save) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( 'Configurations saved', 'wpmercure' ); ?></p>
            </div>
            <?php
        }
?>
        <h1><?= get_admin_page_title() ?></h1>
        <form method="post">
            <input type="hidden" name="page" value="wpmercure-admin">
            <?php wp_nonce_field( 'wpmercure-admin' ); ?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="hub-url-back">
                                <?= __('Hub url for server', 'wpmercure') ?>
                            </label>
                        </th>
                        <td><input name="hub-url-back" type="text" id="hub-url-back" value="<?= $config['HUB_URL'] ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="hub-url-front">
                                <?= __('Hub url for client', 'wpmercure') ?>
                            </label>
                        </th>
                        <td><input name="hub-url-front" type="text" id="hub-url-front" value="<?= $config['HUB_PUBLIC_URL'] ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="jwt-token">
                                <?= __('JWT token', 'wpmercure') ?>
                            </label>
                        </th>
                        <td><input name="jwt-token" type="text" id="jwt-token" value="<?= $config['JWT'] ?>" class="regular-text"></td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?= __('Save') ?>"></p>
        </form>
<?php
    }

    public function renderMenuFunctions() {
        $config = WpMercure::getConf('features');
        $save = false;
        if (array_key_exists('page', $_POST) && wp_verify_nonce($_POST['_wpnonce'], 'wpmercure-features')) {
            $livePost = false;
            $liveTaxonomies = false;
            $selectorLivePost = '';

            if (array_key_exists('live-post', $_POST) && $_POST['live-post'] === 'on') {
                $livePost = true;
            }

            if (array_key_exists('live-taxonomies', $_POST) && $_POST['live-taxonomies'] === 'on') {
                $liveTaxonomies = true;
            }

            if (array_key_exists('selector-live-post', $_POST)) {
                $selectorLivePost = htmlentities($_POST['selector-live-post']);
            }

            $config = ['LIVE_POST' => $livePost, 'LIVE_TAXONOMIES' => $liveTaxonomies, 'SELECTOR_LIVE_POST' => $selectorLivePost];
            $save = WpMercure::saveConfig('features', $config);
        }

        if ($save) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( 'Configurations saved', 'wpmercure' ); ?></p>
            </div>
            <?php
        }
?>
        <h1><?= __('Plugin features', 'wpmercure') ?></h1>
        <form method="post">
            <input type="hidden" name="page" value="wpmercure-functions">
            <?php wp_nonce_field( 'wpmercure-features' ); ?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="live-post">
                            <?= __('Live post', 'wpmercure') ?>
                        </label>
                    </th>
                    <td><input type="checkbox" name="live-post" id="live-post" <?= ($config['LIVE_POST'] === true) ? 'checked' : '' ?>></td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="live-post">
                            <?= __('CSS Selector update live post', 'wpmercure') ?>
                        </label>
                    </th>
                    <td><input type="text" placeholder="Ex: .entry-content" name="selector-live-post" id="selector-live-post" value="<?= $config['SELECTOR_LIVE_POST'] ?>"></td>
                </tr>
                <tr style="display: none">
                    <th scope="row">
                        <label for="live-taxonomies">
                            <?= __('Live taxonomy pages', 'wpmercure') ?>
                        </label>
                    </th>
                    <td><input type="checkbox" name="live-taxonomies" id="live-taxonomies" <?= ($config['LIVE_TAXONOMIES'] === true) ? 'checked' : '' ?>></td>
                </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?= __('Save') ?>"></p>
        </form>
<?php
    }
}
