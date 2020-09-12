<?php
namespace IqbalRony;
if (!defined('ABSPATH')) exit; // Exit if accessed directly


class VersionSwitcher {

	private static $instance = null;
	
	public function init() {

		/**
		 * Enqueue Admin Scripts
		 */
		add_action('admin_enqueue_scripts', array( $this, 'admin_scripts') );

		self::include_file();

		add_action( 'admin_menu', array( $this, 'menu_page' ) );

		add_filter( 'plugin_action_links', array( $this, 'set_add_action_links_in_plugin_page' ), PHP_INT_MAX, 4 );

		Version::set_plugin_info();

		// self::switch_version_apply();

		// add_action( 'admin_post_wpvs_switcher', [ $this, 'version_switch' ] );
	}


	function set_add_action_links_in_plugin_page( $actions, $plugin_file, $plugin_data, $context ) {
		if ( isset( $plugin_data['slug'] ) && isset( $plugin_data['Name'] ) ) {
			$query = '?vs_plugin_slug='.$plugin_data['slug'].'&vs_plugin_name='.$plugin_data['Name'];
			$actions = array_merge( $actions, array(
				'<a href="' . admin_url( 'plugins.php' ) .$query . '">' .
					__( 'Add To Version Switcher', 'download-plugins-dashboard' ) . '</a>' )
			);
		}
		return $actions;
	}



	/**
	 * Function for enqueue all scripts
	 */
	public function admin_scripts() {

		wp_enqueue_style('wpvs-admin-css', wpvs_plugin_url('/assets/css/version-switcher-admin.css'), '', '');

		wp_enqueue_script('wpvs-admin-js', wpvs_plugin_url('/assets/js/version-switcher-admin.js'), array('jquery'), '', true);
		
		wp_localize_script('wpvs-admin-js','wpvs_admin_localize',array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'wpvs_nonce' => wp_create_nonce('wpvs_version_switcher'),
		));
	}



	/**
	 * Include files
	 */
	public static function include_file() {
		require_once(wpvs_get_plugin_path('inc/version.php'));
		require_once(wpvs_get_plugin_path('inc/switcher.php'));
		require_once(wpvs_get_plugin_path('inc/ajax-handler.php'));
	}



	/**
	 * Add menu page
	 */
	public function menu_page () {
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
	public function menu_page_markup () {
		require_once wpvs_get_plugin_path( 'templates/settings.php' );
	}


	
	/**
	 * Switch Version Apply
	 */
	public static function switch_version_apply(){

		// $plugin_page = admin_url( 'plugins.php' );
		// echo '<pre>';
		// var_dump( $_POST['wpvs_submit'] !== 'submit' && !wp_verify_nonce( $_POST['wpvs_nonce'], 'wpvs_version_switcher' ) );
		// echo '</pre>';

		if ( $_POST['wpvs_submit'] !== 'submit' && !wp_verify_nonce( $_POST['wpvs_nonce'], 'wpvs_version_switcher' ) ) {
			return;
		}

		if ( empty( $_POST['slug'] ) && empty( $_POST['version'] ) ) {
			return;
		}

		$all_slugs = get_transient( wpvs_get_key( 'added_plugin' ) );
		if( is_array($all_slugs) && !array_key_exists( $_POST['slug'], $all_slugs) ){
			return;
		}
		
		$all_version = get_transient( wpvs_get_key( $_POST['slug'] ) );
		if( is_array($all_version) && !in_array( $_POST['version'], $all_version) ){
			return;
		}
		
		Version::version_switch( $_POST['slug'], $_POST['version'] );

    }


	public static function instance() {
		self::$instance = new self();
        return self::$instance;
    }
	

}