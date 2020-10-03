<?php

namespace IqbalRony\VersionSwitcher;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

Version::switch_version_apply();
$all_plugins = Version::get_all_installed_plugin();

?>
	<div class="irvs-settings-area">
		<h2 class="irvs-settings-title"><?php esc_html_e( 'Version Switcher', 'version-switcher' ); ?></h2>
		<p class="main-desc description"><?php esc_html_e( 'If you facing any issue with any installed plugin\'s version, you can switchback to the previous version easily from here. you can switch between any previous version of a plugin. Please select the plugin name & version and click the "Switch Version" button.', 'version-switcher' ); ?></p>
		<div class="irvs-settings">

			<?php if( $all_plugins ): ?>
			<form method="post" class="user_switch" action="">
				<div class="irvs-plugin-name-area">
					<div class="irvs-plugin-name">
						<h2><?php esc_html_e( 'Plugin Names', 'version-switcher' ); ?></h2>
					</div>
					<div class="irvs-plugin-name-select">
						<?php 
						$name_html = '<select name="slug" required>';
						$name_html .= "<option value='' >".__( 'Select Plugin', 'version-switcher' )."</option>";
						foreach ( $all_plugins as $key => $value ) {
							$name_html .= "<option value='{$value['plugin_slug']}' >{$value['name']}</option>";
						}
						$name_html .= '</select>';
						echo $name_html;
						
						?>
						<p class="description"><?php esc_html_e( 'Select a plugin name from select box.', 'version-switcher' ); ?></p>
					 </div>
				</div>

				
				<div class="irvs-plugin-version-area">
					<div class="irvs-plugin-version">
						<h2><?php esc_html_e( 'Plugin Versions', 'version-switcher' ); ?></h2>
					</div>
					<div class="irvs-plugin-version-select">
						<?php 
						$version_html = '<select name="version" disabled="disabled" required>';
						
						$version_html .= "<option value='' >".__( 'Select Version', 'version-switcher' )."</option>";
						$version_html .= '</select>';
						echo $version_html;
						?>
						<p class="description"><?php esc_html_e( 'Select a version number from select box.', 'version-switcher' ); ?></p>
						<p class="notice notice-warning wp_org">></p>
					</div>
				</div>

				<input type="hidden" name="irvs_nonce" value="<?php echo wp_create_nonce( 'irvs_version_switcher' ); ?>">
					<br><br>
				<button class="wp-core-ui button-primary" name="irvs_submit" value="submit" type="submit">
					<?php esc_html_e( 'Switch Version', 'version-switcher' ); ?>
				</button>
				
			</form>
			<?php endif; ?>
		</div>
	</div>
<?php
