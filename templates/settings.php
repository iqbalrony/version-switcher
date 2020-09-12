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
	Version::version_switch( $_POST['slug'], $_POST['version'] );
	
}

$transient_key = wpvs_get_key( 'added_plugin' );
$all_plugins = get_transient( $transient_key );
?>
	<div class="wpvs-settings-area">
		<h2 class="wpvs-settings-title"><?php esc_html_e( 'Version Switcher', '@version-switcher' ); ?></h2>
		<div class="wpvs-settings">
			<?php
				if( empty($all_plugins) || !is_array($all_plugins) ){
					// echo '<span class="notice notice-info">' . esc_html__( 'No Plugin Added to list', '@version-switcher' ) . '</span>';

					printf('<span class="notice notice-info">%s<a href="%s">%s</a></span>',
					__( 'No Plugin Added to Version Switcher. ', '@version-switcher' ),
					admin_url( 'plugins.php' ),
					__( 'Please ADD.', '@version-switcher' )
				);
				}
			?>
			<?php if( $all_plugins ): ?>
			<form method="post" class="user_switch" action="">
				<div class="wpvs-plugin-name-area">
					<div class="wpvs-plugin-name">
						<h2><?php esc_html_e( 'Select Plugin', '@version-switcher' ); ?></h2>
					</div>
					<div class="wpvs-plugin-name-select">
						<?php 
						$name_html = '<select name="slug" required>';
						$selected_value = $_POST['slug'] ? $_POST['slug'] : '';

						$name_html .= "<option value='' >".esc_html__( 'Select Plugin', '@version-switcher' )."</option>";

						foreach ( $all_plugins as $key => $name ) {
							$selected =  $selected_value == $key ? 'selected="selected"' : '';
							$name_html .= "<option value='{$key}' {$selected}>{$name}</option>";
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
						$selected_vs = $_POST['version'] ? $_POST['version'] : '';
						
						$version_html .= "<option value='' >".esc_html__( 'Select Version', '@version-switcher' )."</option>";
						$version_html .= "<option value='demo' >--demo--</option>";
						$version_html .= "<option value='dem2' >--demo2--</option>";
						foreach ( $all_versions as $version ) {
							$selected =  $selected_vs == $version ? 'selected="selected"' : '';
							$version_html .= "<option value='{$version}' {$selected}>$version</option>";
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
