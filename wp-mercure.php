<?php
/*
Plugin Name: WP Mercure
Description: WordPress integration of Mercure protocol
Author: Clément Décou
Author URI: https://www.clement-decou.fr
Version: 0.1
*/
namespace WpMercure;

use Symfony\Component\Mercure\Jwt\StaticJwtProvider;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use WpMercure\Admin\WpMercureAdmin;

require __DIR__ . '/vendor/autoload.php';

class WpMercure {
    public static function init() {
        if (is_admin()) {
            new WpMercureAdmin();
        } else {
            new WpMercureFront(self::getConf());
        }
    }

    /**
     * @return array
     */
    public static function getConf() {
        return include __DIR__ . '/config/configuration.php';
    }

    public static function saveConfig(string $fileName, array $config) {
        return file_put_contents(__DIR__ . '/config/' . $fileName . '.php', "<?php \n return " . var_export($config, true) . ';');
    }
}
WpMercure::init();
