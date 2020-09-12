<?php

namespace IqbalRony;
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
// if ( $_POST['wpvs_allow_users_submit'] && wp_verify_nonce( $_POST['wpvs_allow_users_nonce'], 'wpvs_allow_users_nonce' ) ) {

	$update_plugins = get_site_transient( 'update_plugins' );

	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	 
	$all_plugins = get_plugins();
	$all_plugins_slug = [];
	foreach ( $all_plugins as $key => $value) {
		$slug = substr( $key, 0, strpos($key,'/') );
		if( empty($slug) ){
			$slug = basename( $key, '.php' );
		}
		$all_plugins_key[$key] = [
			'name' => $value['Name'],
			'text_domain' => $value['TextDomain'],
			'plugin_slug' => $slug,
		];
	}
	// Save the data to the error log so you can see what the array format is like.
	// print_r( $all_plugins, true );
	$plugin_slug = basename( ELEMENTOR__FILE__, '.php' );
	$plugin_slug = basename( 'advanced-custom-fields/acf.php', '.php' );
	// $slug = plugin_basename( __FILE__ );
// echo '<pre>';
// var_dump( WPVS_TOOLKIT__FILE__,ELEMENTOR__FILE__,ELEMENTOR_PLUGIN_BASE,$plugin_slug, count($all_plugins_key), $all_plugins);
// echo '</pre>';
echo '<pre>';
// var_dump($update_plugins);
echo '</pre>';

//advanced-custom-fields 5.8.0

// Version::version_switch( 'advanced-custom-fields', '5.8.0' );

$rollback_versions = get_transient( 'wpvs_rollback_versions_' );
if( $_POST['version'] && $_POST['slug'] ){
	// echo 'i m in';
	if ( empty( $_POST['version'] ) || ! in_array( $_POST['version'], $rollback_versions ) ) {
		wp_die( __( 'Error occurred, The version selected is invalid. Try selecting different version.', 'elementor' ) );
	}

	
	//Version::version_switch( $_POST['slug'], $_POST['version'] );
	// Version::version_switch( 'advanced-custom-fields', '5.8.0' );

}
// $page_url = admin_url( 'admin.php?page=version-switcher' );

$all_plugins = Version::get_all_installed_plugin();
?>
	<div class="wpvs-settings-area">
		<h2 class="wpvs-settings-title"><?php esc_html_e( 'Version Switcher', '@version-switcher' ); ?></h2>
		<div class="wpvs-settings">
			<form method="post" class="user_switch" action="">
				<div class="wpvs-plugin-name-area">
					<div class="wpvs-plugin-name">
						<h2><?php esc_html_e( 'Select Plugin', '@version-switcher' ); ?></h2>
					</div>
					<div class="wpvs-plugin-name-select">
						<?php 
						
						$name_html = '<select name="slug">';
						$selected_value = $_POST['slug'] ? $_POST['slug'] : '';

						$name_html .= "<option value='' >".esc_html__( 'Select Plugin', '@version-switcher' )."</option>";

						foreach ( $all_plugins as $plugin ) {
							$selected =  $selected_value == $plugin['plugin_slug'] ? 'selected="selected"' : '';
							$name_html .= "<option value='{$plugin['plugin_slug']}' {$selected}>{$plugin['name']}</option>";
						}
						$name_html .= '</select>';
						echo $name_html;
						?>
					 </div>
				</div>

				<div class="wpvs-plugin-version-area">
					<div class="wpvs-plugin-version">
						<h2><?php esc_html_e( 'Select Version', '@version-switcher' ); ?></h2>
					</div>
					<div class="wpvs-plugin-version-select">
						<?php 
						$rollback_versions = get_transient( 'wpvs_version_switcher_'.$all_plugins[0]['transient_key'] );
						$rollback_versions = $rollback_versions ? $rollback_versions : [];
						
						$rollback_html = '<select name="version">';
						$selected_vs = $_POST['version'] ? $_POST['version'] : '';
						
						$rollback_html .= "<option value='' >".esc_html__( 'Select Version', '@version-switcher' )."</option>";
						$rollback_html .= "<option value='demo' >--demo--</option>";
						$rollback_html .= "<option value='dem2' >--demo2--</option>";
						foreach ( $rollback_versions as $version ) {
							$selected =  $selected_vs == $version ? 'selected="selected"' : '';
							$rollback_html .= "<option value='{$version}' {$selected}>$version</option>";
						}
						$rollback_html .= '</select>';
						echo $rollback_html;
						?>
						<!-- <i class="dashicons-before dashicons-update-alt"></i> -->
					 </div>
				</div>

				<input type="hidden" name="action" value="wpvs_switch">
				<input type="hidden" name="wpvs_nonce" value="<?php echo wp_create_nonce( 'wpvs_nonce' ); ?>">
					   <br>
					   <br>
				<button class="wp-core-ui button-primary" name="wpvs_submit" value="submit"
				        type="submit"><?php esc_html_e( 'Save Changes', '@version-switcher' ); ?></button>
			</form>
		</div>
	</div>
<?php
