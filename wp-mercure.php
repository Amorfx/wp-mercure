<?php
/*
Plugin Name: WP Mercure
Description: WordPress integration of Mercure protocol
Author: Clément Décou
Author URI: https://www.clement-decou.fr
Version: 0.1
*/
namespace WpMercure;

use WpMercure\Admin\WpMercureAdmin;
use WpMercure\Features\LivePost;

require __DIR__ . '/vendor/autoload.php';

class WpMercure {
    static $configurations;
    static $featuresConfig;
    static $featuresClass;

    public static function init() {
        self::$configurations = false;
        self::$featuresConfig = false;

        if (is_admin()) {
            new WpMercureAdmin();
        } else {
            new WpMercureFront(array_merge(self::getConf(), self::getConf('features')));
        }

        self::$featuresClass = apply_filters('wpmercure_features_array', [LivePost::class]);
        self::initFunctionnalities();
    }

    public static function initFunctionnalities() {
        $conf = self::getConf('features');
        foreach (self::$featuresClass as $aClass) {
            if ($conf[$aClass::CONFIG_ID] === true) {
                $f = new $aClass();
                $f->init();
            }
        }
    }

    /**
     * @param string $file
     *
     * @return array
     */
    public static function getConf($file = 'configuration') {
        $conf = include __DIR__ . '/config/' . $file . '.php';
        switch ($file) {
            case 'configuration':
                if (self::$configurations) {
                    return self::$configurations;
                } else {
                    self::$configurations = $conf;
                }
                break;

            case 'features':
                if (self::$featuresConfig) {
                    return self::$featuresConfig;
                } else {
                    self::$featuresConfig = $conf;
                }
                break;
        }
        return $conf;
    }

    public static function saveConfig(string $fileName, array $config) {
        return file_put_contents(__DIR__ . '/config/' . $fileName . '.php', "<?php \n return " . var_export($config, true) . ';');
    }
}
WpMercure::init();
