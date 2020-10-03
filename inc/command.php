<?php

namespace IqbalRony\VersionSwitcher;

/**
 * WP Version Switcher Commands
 */
class Command extends \WP_CLI_Command {

	/**
	 * Change a plugin version
	 *
	 * ## OPTIONS
	 *
	 * <slug>
	 * : Slug of the plugin
	 *
	 *
	 * <version>
	 * : Version of the plugin you want to change to
	 *
	 *
	 * ## EXAMPLES
	 *
	 *     wp vs change akismet 4.1.1
	 *
	 * @when after_wp_load
	 */
	public function change( $args, $assoc_args ) {
		list( $slug, $version ) = $args;
		\WP_CLI::line( "Change version of the $slug plugin..." );
		if ( is_wp_error( Version::version_switch( $slug, $version ) ) ) {
			\WP_CLI::error( 'Something went wrong' );
		}
		\WP_CLI::success( 'Changed version successfully' );
	}
}