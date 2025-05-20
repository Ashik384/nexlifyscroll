<?php
/**
 * Plugin Name: Nexlify Scroll
 * Plugin URI: https://nexlify.com
 * Description: A simple plugin to add scroll functionality to your WordPress site.
 * Version: 1.0.0
 * Author: Ashikul Islam
 * Author URI: https://nexlify.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: nexlifyscroll
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('NEXLIFYSCROLL_VERSION', '1.0.0');
define('NEXLIFYSCROLL_PATH', plugin_dir_path(__FILE__));
define('NEXLIFYSCROLL_URL', plugin_dir_url(__FILE__));

/**
 * Core plugin class
 */
class NexlifyScroll_Core {
    private static $instance = null;
    private $components = [];

    // Get singleton instance
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Load text domain
        add_action('plugins_loaded', [$this, 'load_textdomain']);

        // Initialize components
        $this->init_components();
    }

    public function load_textdomain() {
        load_plugin_textdomain('nexlifyscroll', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    private function init_components() {
        // Include component classes
        require_once NEXLIFYSCROLL_PATH . 'includes/class-settings.php';
        require_once NEXLIFYSCROLL_PATH . 'includes/class-frontend.php';
        // require_once NEXLIFYSCROLL_PATH . 'includes/class-activation.php';

        // Instantiate components
        $this->components['settings'] = new NexlifyScroll_Settings();
        $this->components['frontend'] = new NexlifyScroll_Frontend();
        // $this->components['activation'] = new NexlifyScroll_Activation();
    }

    // Get component instance
    public function get_component($name) {
        return isset($this->components[$name]) ? $this->components[$name] : null;
    }
}

// Initialize plugin
function nexlifyscroll() {
    return NexlifyScroll_Core::get_instance();
}
nexlifyscroll();
?>