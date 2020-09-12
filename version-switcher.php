<?php
/*
Plugin Name: Version Switcher
Plugin URI: https://example.com
Description: this is custom post type messonary plugin.
Author: No One
Version: 1.0.0
Author URI: https://example.com
Text Domain: version-switcher @version-switcher
*/

// version-switcher
// Exit if accessed directly
if (!defined('ABSPATH')) {
	die;
}


define( 'WPVS_VERSION', '1.0.0' );
define( 'WPVS__FILE__', __FILE__ );
define('WPVS_DIR_PATH', plugin_dir_path(WPVS__FILE__));
define('WPVS_KEY', 'wpvs_version_switcher_');

if (!function_exists('wpvs_get_plugin_path')) {
	function wpvs_get_plugin_path($file) {
		return WPVS_DIR_PATH . $file;
	}
}

if (!function_exists('wpvs_plugin_url')) {
	function wpvs_plugin_url($url) {
		return plugins_url($url, WPVS__FILE__);
	}
}


if (!defined('WPVS_MENU_PAGE_SLUG')) {
	$menu_slug = sanitize_key( 'version-switcher' );
	define('WPVS_MENU_PAGE_SLUG', $menu_slug);
}

add_action('plugins_loaded', 'wpvs_start_version_swithc');
function wpvs_start_version_swithc() {

    require_once(wpvs_get_plugin_path('inc/plugin.php'));
    require_once(wpvs_get_plugin_path('inc/functions.php'));

    IqbalRony\VersionSwitcher::instance()->init();
}


