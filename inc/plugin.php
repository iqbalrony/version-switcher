<?php

namespace IqbalRony\VersionSwitcher;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


class VersionSwitcher {

	private static $instance = null;

	private function __construct() {
		add_action( 'init', [ $this, 'i18n' ] );
	}
	
	/**
	 * Text Domain Register.
	 */
	public function i18n() {
		load_plugin_textdomain(
			'version-switcher',
			false,
			dirname( plugin_basename( IRVS__FILE__ ) ) . '/i18n/'
		);
	}

	public function init() {

		//enqueue admin style & scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		//includes file
		self::include_file();

		//add plugin setting page
		add_action( 'admin_menu', array( $this, 'menu_page' ) );

		//regiter commands
		$this->registerCommands();

	}


	/**
	 * Function for enqueue all scripts
	 */
	public function admin_scripts() {

		wp_enqueue_style( 'irvs-admin-css', irvs_plugin_url( '/assets/css/version-switcher-admin.css' ), '', '' );

		wp_enqueue_script( 'irvs-admin-js', irvs_plugin_url( '/assets/js/version-switcher-admin.js' ), array( 'jquery' ), '', true );

		wp_localize_script( 'irvs-admin-js', 'irvs_admin_localize', array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'irvs_nonce' => wp_create_nonce( 'irvs_version_switcher' ),
		) );
	}


	/**
	 * Include files
	 */
	public static function include_file() {
		require_once( irvs_get_plugin_path( 'inc/version.php' ) );
		require_once( irvs_get_plugin_path( 'inc/switcher.php' ) );
		require_once( irvs_get_plugin_path( 'inc/ajax-handler.php' ) );
	}


	/**
	 * Add menu page
	 */
	public function menu_page() {
		add_menu_page(
			__( 'Version Switcher', 'version-switcher' ),
			__( 'Version Switcher', 'version-switcher' ),
			'manage_options',
			IRVS_MENU_PAGE_SLUG,
			array( $this, 'menu_page_markup' ),
			'dashicons-update-alt'
		);
	}


	/**
	 * Include setting page markup
	 */
	public function menu_page_markup() {
		require_once irvs_get_plugin_path( 'templates/settings.php' );
	}

	/**
	 * Register WP_CLI commnads
	 */
	public function registerCommands() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once irvs_get_plugin_path( 'inc/command.php' );
			\WP_CLI::add_command( 'vs', Command::class );
		}
	}

	/**
	 * Create this class instance
	 */
	public static function instance() {
		self::$instance = new self();

		return self::$instance;
	}


}
