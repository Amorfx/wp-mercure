<?php

namespace WpMercure\Admin;

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

    }

    public function renderMenuFunctions() {

    }
}
