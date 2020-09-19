<?php

namespace IqbalRony;
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( $_POST['slug'] && $_POST['version'] ) {
	echo '<pre>';
	var_dump( $_POST['slug'] );
	var_dump( $_POST['version'] );
	echo '</pre>';
	// Version::version_switch( $_POST['slug'], $_POST['version'] );
	Version::switch_version_apply( );
	
}

// $transient_key = wpvs_get_key( 'added_plugin' );
// $all_plugins = get_transient( $transient_key );
$all_plugins = Version::get_all_installed_plugin();
// echo '<pre>';
// var_dump( Version::get_all_installed_plugin() );
// var_dump( Version::get_updates_plugin() );
// var_dump( get_plugin_updates() );
// var_dump( get_site_transient( 'update_plugins' ) );
// var_dump(get_all_installed_plugin_data());
// echo '</pre>';

// $all_slugs = \IqbalRony\Version::get_all_installed_plugin();
// 	if( $all_slugs ){
// 		foreach ( $all_slugs as $key => $value ) {
// 			// delete_transient( wpvs_get_key( $value['plugin_slug'] ) );
// 			$all_version = get_transient(  wpvs_get_key( $value['plugin_slug'] ) );
// 			$need_cache_update = \IqbalRony\Version::is_version_exist_in_cache( (array) $all_version, str_replace( "-", "_", $value['plugin_slug']) );
// 			// $need_update = in_array( $update_info[$key], $all_versions );
// 			echo '<pre>';
// 			var_dump( $value['name'] );
// 			var_dump( 'Need update cache? = ', $need_cache_update );
// 			var_dump( array_merge(['5.9.2'],$all_version) );
// 			echo '</pre>';
// 		}
// 		// delete_transient( $transient_key );
// 		// echo '<pre>';
// 		// var_dump( $all_slugs );
// 		// echo '</pre>';
// 	}
?>
	<div class="wpvs-settings-area">
		<h2 class="wpvs-settings-title"><?php esc_html_e( 'Version Switcher', '@version-switcher' ); ?></h2>
		<div class="wpvs-settings">

			<?php if( $all_plugins ): ?>
			<form method="post" class="user_switch" action="">
				<div class="wpvs-plugin-name-area">
					<div class="wpvs-plugin-name">
						<h2><?php esc_html_e( 'Select Plugin', '@version-switcher' ); ?></h2>
					</div>
					<div class="wpvs-plugin-name-select">
						<?php 
						$name_html = '<select name="slug" required>';
						// $selected_value = $_POST['slug'] ? $_POST['slug'] : '';

						$name_html .= "<option value='' >".esc_html__( 'Select Plugin', '@version-switcher' )."</option>";

						foreach ( $all_plugins as $key => $value ) {
							// $selected =  $selected_value == $key ? 'selected="selected"' : '';
							$name_html .= "<option value='{$value['plugin_slug']}' >{$value['name']}</option>";
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
						$all_versions = get_transient( 'wpvs_version_switcher_'.$all_plugins[0]['transient_key'] );
						$all_versions = $all_versions ? $all_versions : [];
						
						$version_html = '<select name="version" disabled="disabled" required>';
						// $selected_vs = $_POST['version'] ? $_POST['version'] : '';
						
						$version_html .= "<option value='' >".esc_html__( 'Select Version', '@version-switcher' )."</option>";
						$version_html .= "<option value='demo' >--demo--</option>";
						$version_html .= "<option value='dem2' >--demo2--</option>";
						foreach ( $all_versions as $version ) {
							// $selected =  $selected_vs == $version ? 'selected="selected"' : '';
							$version_html .= "<option value='{$version}'>$version</option>";
						}
						$version_html .= '</select>';
						echo $version_html;
						?>
						<!-- <i class="dashicons-before dashicons-update-alt"></i> -->
					</div>
				</div>

				<input type="hidden" name="wpvs_nonce" value="<?php echo wp_create_nonce( 'wpvs_version_switcher' ); ?>">
					<br>
					<br>
				<button class="wp-core-ui button-primary" name="wpvs_submit" value="submit" type="submit">
					<?php esc_html_e( 'Switcher', '@version-switcher' ); ?>
				</button>
				
			</form>
			<?php endif; ?>
		</div>
	</div>
<?php
