<?php
/**
 * Plugin Name: Version Switcher
 * Description: This is a very simple plugin which will help you to switch instantly between all plugin's version which exists in WordPress org and there is no anxiety about download and upload task because just one click will do that for you.
 * Author: IqbalRony
 * Author URI: http://www.iqbalrony.com
 * Version: 1.0.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: version-switcher
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define( 'IRVS_VERSION', '1.0.0' );
define( 'IRVS__FILE__', __FILE__ );
define( 'IRVS_DIR_PATH', plugin_dir_path( IRVS__FILE__ ) );
define( 'IRVS_KEY', 'irvs_version_switcher_' );

/**
 * Get plugin path.
 */
if ( ! function_exists( 'irvs_get_plugin_path' ) ) {
	function irvs_get_plugin_path( $file ) {
		return IRVS_DIR_PATH . $file;
	}
}

/**
 * Get plugin url.
 */
if ( ! function_exists( 'irvs_plugin_url' ) ) {
	function irvs_plugin_url( $url ) {
		return plugins_url( $url, IRVS__FILE__ );
	}
}

/**
 * defining plugin menu page slug.
 */
if ( ! defined( 'IRVS_MENU_PAGE_SLUG' ) ) {
	$menu_slug = sanitize_key( 'version-switcher' );
	define( 'IRVS_MENU_PAGE_SLUG', $menu_slug );
}

/**
 * Start the journey of version switcher.
 */
function irvs_start_version_switch() {
	require_once( irvs_get_plugin_path( 'inc/plugin.php' ) );
	require_once( irvs_get_plugin_path( 'inc/functions.php' ) );

	IqbalRony\VersionSwitcher\VersionSwitcher::instance()->init();
}
add_action( 'plugins_loaded', 'irvs_start_version_switch' );

// Delete all the cash of this plugin from database
register_deactivation_hook( __FILE__, 'irvs_delete_all_cache' );
