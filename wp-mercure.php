<?php
/*
Plugin Name: WP Mercure
Description: WordPress integration of Mercure protocol. Permit realtime update post.
Author: Clément Décou
Author URI: https://www.clement-decou.fr
Version: 0.1
Text Domain: wpmercure
Domain Path: /languages
*/
namespace WpMercure;

use Symfony\Component\Mercure\Jwt\StaticJwtProvider;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use WpMercure\Admin\WpMercureAdmin;
use WpMercure\Features\LivePost;

require __DIR__ . '/vendor/autoload.php';

/**
 * Class WpMercure
 * Main class of the plugin
 * Manage features
 * @package WpMercure
 * @since 0.1
 */
class WpMercure {
    static $configurations;
    static $featuresConfig;
    static $featuresClass;
    static $publisher;

    public static function init() {
        self::$configurations = false;
        self::$featuresConfig = false;
        self::$publisher = false;

        if (is_admin()) {
            new WpMercureAdmin();
        }

        self::$featuresClass = apply_filters('wpmercure_features_array', [LivePost::class]);
        self::initFunctionnalities();
        add_action('wpmercure_send_message_post_update', 'WpMercure\WpMercure::sendPostUpdateMessage');

        // Languages
        add_action( 'plugins_loaded', 'WpMercure\WpMercure::loadLangFile' );
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
        $conf = file_get_contents(__DIR__ . '/config/' . $file . '.json');
        switch ($file) {
            case 'configuration':
                if (self::$configurations) {
                    return self::$configurations;
                } else {
                    self::$configurations = json_decode($conf, true);
                }
                break;

            case 'features':
                if (self::$featuresConfig) {
                    return self::$featuresConfig;
                } else {
                    self::$featuresConfig = json_decode($conf, true);
                }
                break;
        }
        return json_decode($conf, true);
    }

    public static function saveConfig(string $fileName, array $config) {
        return file_put_contents(__DIR__ . '/config/' . $fileName . '.json', json_encode($config));
    }

    /**
     * @return Publisher
     */
    private static function getPublisher() {
        if (self::$publisher !== false) {
            return self::$publisher;
        }
        $configuration = self::getConf();
        self::$publisher = new Publisher($configuration['HUB_URL'], new StaticJwtProvider($configuration['JWT']));
        return self::$publisher;
    }

    /**
     * Send message to a specific topic
     * @param $topic
     * @param $data
     *
     * @return string
     */
    public static function sendMessage($topic, string $data) {
        $publisher = self::getPublisher();
        try {
            return $publisher(new Update($topic, $data));
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    /**
     * Function of wpmercure_send_message_post_update action
     *
     * @param $postID
     * @param string $message
     */
    public static function sendPostUpdateMessage($postID, $message = '') {
        if (empty($message)) {
            $message = __('This post has been updated, click to reload post.', 'wpmercure');
        }
        $data = [
            'post_content' => apply_filters('the_content',get_the_content(null, false, $postID)),
            'message' => $message,
            'selector' => '',
        ];
        WpMercure::sendMessage(get_permalink($postID), json_encode($data));
    }

    public static function getPluginUrl($path) {
        return plugin_dir_url(__FILE__) . $path;
    }

    public static function loadLangFile() {
        load_plugin_textdomain( 'wpmercure', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }
}
WpMercure::init();
