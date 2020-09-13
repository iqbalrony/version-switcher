<?php


/* 
	Get transient key
*/
function wpvs_get_key( $slug ){
	$transient_key = WPVS_KEY. str_replace( "-", "_", $slug );
	return $transient_key;
}

/* 
	Delete All cache
*/
function wpvs_delete_all_cache(){
	$transient_key = wpvs_get_key( 'added_plugin' );
	$all_slugs = get_transient( $transient_key );
	if( $all_slugs ){
		foreach ( $all_slugs as $key => $value ) {
			delete_transient( wpvs_get_key( $key ) );
		}
		delete_transient( $transient_key );
	}
}

// add_action('wp_footer','wpvs_delete_all_cache');








/* 
=====================================================================
	This is Garbage Codes, it will remove after work is done
=========================================================================
*/

// wpvs_version_switcher_advanced_custom_fields
// wpvs_version_switcher_advanced_wp_reset
// wpvs_version_switcher_akismet
// wpvs_version_switcher_elementskit
// wpvs_version_switcher_essential_addons_elementor
// wpvs_version_switcher_wp_user_switch

// WPVS
function _delete_transient() {
    // check status here or check if post data has been changed
    // delete_transient( 'wpvs_rollback_versions_' );
    // delete_transient( 'wpvs_plugin_info_' );
	// delete_transient( 'wpvs_plugin_api' );

	// $all_slugs = get_all_installed_plugin_info();
	// $transient_key = 'wpvs_version_switcher_';
	// foreach ( $all_slugs as $key => $value ) {
	// 	$transient_key = $transient_key. str_replace("-","_",$value);
	// 	delete_transient( $transient_key );
	// }

}
_delete_transient();
// wp-user-switch

// delete_transient( wpvs_get_key( 'added_plugin' ) );
// delete_transient( wpvs_get_key( 'advanced-wp-reset' ) );

// echo '<pre>';
// var_dump(get_transient( WPVS_KEY.'added_plugin' ));
// var_dump(get_transient( WPVS_KEY.'advanced_custom_fields' ));
// echo '</pre>';



function get_all_installed_plugin_info(){
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
		$all_plugins_key[] = [
			'key' => $key,
			'name' => $value['Name'],
			'text_domain' => $value['TextDomain'],
			'plugin_slug' => $slug,
			'transient_key' => str_replace("-","_",$slug),
		];
		$all_plugins_slug[] = $slug;
	}
	
	return $all_plugins_key;
}

function abc(){
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

	$transient_key = wpvs_get_key( 'added_plugin' );

	// delete_transient( $transient_key );

	$all_slugs = get_transient( $transient_key );

	

	foreach ( $all_slugs as $key => $value ) {
		echo '<pre>';
		var_dump($key);
		var_dump($value);
		echo '</pre>';

		$versions_transient_key = wpvs_get_key( $key );
		$version_info = get_transient( $versions_transient_key );
	
		echo '<pre>';
		var_dump($version_info);
		echo '</pre>';
	}
	
	// echo '<pre>';
	// var_dump(array_key_exists('errors',$plgin_info));
	// // var_dump(property_exists($plgin_info,'errors'));
	// var_dump( $plgin_info );
	// echo '</pre>';
}

// add_action('wp_footer','abc');
