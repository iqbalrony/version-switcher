<?php

namespace IqbalRony;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


class VersionSwitcher {

	private static $instance = null;

	public function init() {

		/**
		 * Enqueue Admin Scripts
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		self::include_file();

		add_action( 'admin_menu', array( $this, 'menu_page' ) );

		$this->registerCommands();

	}


	/**
	 * Function for enqueue all scripts
	 */
	public function admin_scripts() {

		wp_enqueue_style( 'wpvs-admin-css', wpvs_plugin_url( '/assets/css/version-switcher-admin.css' ), '', '' );

		wp_enqueue_script( 'wpvs-admin-js', wpvs_plugin_url( '/assets/js/version-switcher-admin.js' ), array( 'jquery' ), '', true );

		wp_localize_script( 'wpvs-admin-js', 'wpvs_admin_localize', array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'wpvs_nonce' => wp_create_nonce( 'wpvs_version_switcher' ),
		) );
	}


	/**
	 * Include files
	 */
	public static function include_file() {
		require_once( wpvs_get_plugin_path( 'inc/version.php' ) );
		require_once( wpvs_get_plugin_path( 'inc/switcher.php' ) );
		require_once( wpvs_get_plugin_path( 'inc/ajax-handler.php' ) );
	}


	/**
	 * Add menu page
	 */
	public function menu_page() {
		add_menu_page(
			__( 'Version Switcher', '@version-switcher' ),
			__( 'Version Switcher', '@version-switcher' ),
			'manage_options',
			WPVS_MENU_PAGE_SLUG,
			array( $this, 'menu_page_markup' ),
			'dashicons-update-alt'
		);
	}


	/**
	 * Include setting page markup
	 */
	public function menu_page_markup() {
		require_once wpvs_get_plugin_path( 'templates/settings.php' );
	}

	public function registerCommands() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once wpvs_get_plugin_path( 'inc/command.php' );
			\WP_CLI::add_command( 'vs', Command::class );
		}
	}


	public static function instance() {
		self::$instance = new self();

		return self::$instance;
	}


}