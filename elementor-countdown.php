<?php
/**
 * Plugin Name: Countdown Timer Elementor
 * Description: A custom Elementor countdown widget with Bangla/English language support and full style controls.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: custom-elementor-countdown
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('CUSTOM_COUNTDOWN_URL', plugins_url('/', __FILE__));
define('CUSTOM_COUNTDOWN_PATH', plugin_dir_path(__FILE__));

/**
 * Main Plugin Class
 */
final class Custom_Elementor_Countdown {

    /**
     * Plugin Version
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Minimum PHP Version
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * Instance
     */
    private static $_instance = null;

    /**
     * Instance
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', [$this, 'i18n']);
        add_action('plugins_loaded', [$this, 'init']);
    }

    /**
     * Load Textdomain
     */
    public function i18n() {
        load_plugin_textdomain('custom-elementor-countdown');
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);
        add_action('elementor/frontend/after_register_styles', [$this, 'widget_styles']);
    }

    /**
     * Admin notice
     * Warning when the site doesn't have Elementor installed or activated.
     */
    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) unset($_GET['activate']);
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'custom-elementor-countdown'),
            '<strong>' . esc_html__('Custom Elementor Countdown', 'custom-elementor-countdown') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'custom-elementor-countdown') . '</strong>'
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     * Warning when the site doesn't have a minimum required Elementor version.
     */
    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'custom-elementor-countdown'),
            '<strong>' . esc_html__('Custom Elementor Countdown', 'custom-elementor-countdown') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'custom-elementor-countdown') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     * Warning when the site doesn't have a minimum required PHP version.
     */
    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'custom-elementor-countdown'),
            '<strong>' . esc_html__('Custom Elementor Countdown', 'custom-elementor-countdown') . '</strong>',
            '<strong>' . esc_html__('PHP', 'custom-elementor-countdown') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Init Widgets
     */
    public function init_widgets() {
        // Include Widget files
        require_once(__DIR__ . '/widgets/countdown-widget.php');
        
        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Custom_Countdown_Widget());
    }

    /**
     * Register Widget Scripts
     */
    public function widget_scripts() {
        wp_register_script('custom-countdown-js', CUSTOM_COUNTDOWN_URL . 'assets/js/countdown.js', ['jquery'], self::VERSION, true);
    }

    /**
     * Register Widget Styles
     */
    public function widget_styles() {
        wp_register_style('custom-countdown-css', CUSTOM_COUNTDOWN_URL . 'assets/css/countdown.css', [], self::VERSION);
    }
}

Custom_Elementor_Countdown::instance();