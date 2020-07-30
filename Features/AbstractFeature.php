<?php

namespace WpMercure\Features;

/**
 * Class AbstractFeature
 *
 * @package WpMercure\Features
 * @since 0.1
 */
abstract class AbstractFeature {
    public function includeNotificationStyle() {
        if (!apply_filters('wpmercure_include_notification_style', true)) {
            return;
        }
        ?>
        <style type="text/css">
            .wpmercure-notification {
                background: white;
                border-radius: 1000px;
                box-shadow: .25rem .25rem 2rem rgba(0,0,0,0.15);
                cursor: pointer;
                left: 50%;
                top: -3rem;
                transform: translate(-50%);
                padding: .75rem 1.5rem;
                position: fixed;
                text-decoration: none;
                z-index: 999;
                transition: top cubic-bezier(.45, 0, .25, 1) .5s;
            }

            .wpmercure-notification.active {
                top: 6rem;
            }
        </style>
        <?php
    }
}
