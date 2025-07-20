<?php
/*
 * Plugin Name: Post to Sendy
 * Plugin URI: https://github.com/michaelspatrick/post-to-sendy
 * Description: Automatically schedules and sends WordPress posts to your Sendy mailing list.
 * Version: 1.2.1
 * Author: Michael Patrick
 * Author URI: https://www.dragonsociety.com
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: post-to-sendy
 */

defined('ABSPATH') || exit;

// Define constants
define('POST_TO_SENDY_PATH', plugin_dir_path(__FILE__));
define('POST_TO_SENDY_URL', plugin_dir_url(__FILE__));

// Include required files
require_once POST_TO_SENDY_PATH . 'includes/settings.php';
require_once POST_TO_SENDY_PATH . 'includes/metabox.php';
require_once POST_TO_SENDY_PATH . 'includes/sendy.php';

// Initialize plugin
function post_to_sendy_init() {
    if (is_admin()) {
        new PostToSendy_Settings();
    }
}
add_action('plugins_loaded', 'post_to_sendy_init');
