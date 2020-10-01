<?php

namespace IqbalRony\WP_Version_Switcher;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Switcher {

	
	protected $package_url;

	protected $version;

	protected $plugin_name;

	protected $plugin_slug;

	/**
	 * constructor.
	 *
	 */
	public function __construct( $args = [] ) {
		foreach ( $args as $key => $value ) {
			$this->{$key} = $value;
		}
	}

	/**
	 * Print inline style.
	 */
	private function print_inline_style() {
		?>
		<style>
			.wrap h1 {
				background: #4d64d5;
				text-align: center;
				color: #fff !important;
				padding: 70px !important;
				text-transform: capitalize;
				letter-spacing: 1px;
			}
		</style>
		<?php
	}

	/**
	 * Apply package.
	 *
	 * Change the plugin data when WordPress checks for updates. This method
	 * modifies package data to update the plugin from a specific URL containing
	 * the version package.
	 */
	protected function apply_package() {
		$update_plugins = get_site_transient( 'update_plugins' );
		if ( ! is_object( $update_plugins ) ) {
			$update_plugins = new \stdClass();
		}

		$plugin_info = new \stdClass();
		$plugin_info->new_version = $this->version;
		$plugin_info->slug = $this->plugin_slug;
		$plugin_info->package = $this->package_url;

		$update_plugins->response[ $this->plugin_name ] = $plugin_info;

		set_site_transient( 'update_plugins', $update_plugins );
	}

	/**
	 * Upgrade.
	 *
	 * Run WordPress upgrade to rollback Elementor to previous version.
	 */
	protected function upgrade() {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

		$title = __( 'Version Switching :- ', 'version-switcher' );
		$title = $title . str_replace( "-", " ", $this->plugin_name ) .' v'. $this->version;

		$upgrader_args = [
			'url' => 'update.php?action=upgrade-plugin&plugin=' . rawurlencode( $this->plugin_name ),
			'plugin' => $this->plugin_name,
			'nonce' => 'upgrade-plugin_' . $this->plugin_name,
			'title' => $title,
		];

		$this->print_inline_style();

		$upgrader = new \Plugin_Upgrader( new \Plugin_Upgrader_Skin( $upgrader_args ) );
		$upgrader->upgrade( $this->plugin_name );
	}

	/**
	 * Run.
	 *
	 * versions switch.
	 */
	public function run() {
		$this->apply_package();
		$this->upgrade();
	}
}
