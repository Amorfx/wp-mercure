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
            __('Functionalities', 'wpmercure'),
            __('Functionalities', 'wpmercure'),
            'manage_options',
            'wpmercure-functions',
            [$this, 'renderMenuFunctions']
        );
    }

    public function renderMenu() {
        $save = false;
        if (array_key_exists('page', $_POST)) {
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

    }
}
