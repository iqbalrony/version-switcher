<?php


/* 
	Get transient key
*/
function wpvs_get_key( $slug ){
	$transient_key = WPVS_KEY. str_replace( "-", "_", $slug );
	return $transient_key;
}








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
	$transient_key = 'wpvs_version_switcher_';
	delete_transient( 'wpvs_plugin_api' );
	$plgin_info = get_transient( 'wpvs_plugin_api' );
	if ( false === $plgin_info ) {
		$plgin_info = plugins_api(
			'plugin_information', [
				'slug' => 'advanced-wp-reset',
				'fields' => [
					'version' => true,
					'versions' => true,
					'contributors' => false,
					'short_description' => false,
					'description' => false,
					'sections' => false,
					'screenshots' => false,
					'tags' => false,
					'donate_link' => false,
					'ratings' => false,
				]
			]
		);
		set_transient( 'wpvs_plugin_api', $plgin_info );
	}


	// $all_slugs = get_all_installed_plugin_info();
	// foreach ( $all_slugs as $key => $value ) {

	// 	$transient_key = $transient_key. str_replace("-","_",$value);

	// 	$rollback_versions = get_transient( $transient_key );
	// 	echo '<pre>';
	// 	var_dump($rollback_versions);
	// 	echo '</pre>';
	// }

	// $plgin_info = get_transient( $transient_key.str_replace("-","_",'essential-addons-for-elementor-lite') );

	// if ( false === $plgin_info ) {
	// 	save_version_data ('advanced-custom-fields','wpvs_plugin_api');
	// }
	
	echo '<pre>';
	var_dump(array_key_exists('errors',$plgin_info));
	// var_dump(property_exists($plgin_info,'errors'));
	var_dump( $plgin_info );
	echo '</pre>';
}

add_action('wp_footer','abc');

// wpvs_version_switcher_advanced_custom_fields
// wpvs_version_switcher_advanced_wp_reset
// wpvs_version_switcher_akismet
// wpvs_version_switcher_elementskit
// wpvs_version_switcher_essential_addons_elementor
// wpvs_version_switcher_happy_elementor_addons
// wpvs_version_switcher_wp_user_switch

// save_version_data ('advanced-custom-fields','wpvs_version_switcher_advanced_custom_fields');
// save_version_data ('advanced-wp-reset','wpvs_version_switcher_advanced_wp_reset');
// save_version_data ('akismet','wpvs_version_switcher_akismet');
// save_version_data ('elementskit','wpvs_version_switcher_elementskit');
// save_version_data ('essential-addons-elementor','wpvs_version_switcher_essential_addons_elementor');
// save_version_data ('happy-elementor-addons','wpvs_version_switcher_happy_elementor_addons');
// save_version_data ('wp-user-switch','wpvs_version_switcher_wp_user_switch');
// save_version_data ('elementskit-lite','wpvs_version_switcher_elementskit_lite');





function get_all_plugin_versions() {

	$all_slugs = get_all_installed_plugin_info();

	$transient_key = 'wpvs_version_switcher_';
	if( !function_exists('plugins_api') ){
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	}

	foreach ( $all_slugs as $key => $value ) {

		$transient_key = $transient_key. str_replace("-","_",$value);

		$rollback_versions = get_transient( $transient_key );
		echo '<pre>';
		var_dump($value);
		var_dump($rollback_versions);
		echo '</pre>';
		if ( false === $rollback_versions ) {
			$max_versions = 20;

			save_version_data ($value,$transient_key,$max_versions);
			
		}
	}
	// $update_plugins = get_site_transient( 'update_plugins' );
	// $plugin_info = new \stdClass();
	// echo '<pre>';
	// var_dump($rollback_versions);
	// var_dump($update_plugins);
	// var_dump($plugin_info);
	// echo '</pre>';
	// return;
	// return $rollback_versions;
}

// get_all_plugin_versions();

// add_action('wp_footer','get_all_plugin_versions');



function save_version_data ($plugin_slug,$transient_key,$return = false,$max_versions =100000){

	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	}

	$plugin_information = plugins_api(
		'plugin_information', [
			'slug' => $plugin_slug,
			'fields' => [
				'version' => true,
				'versions' => true,
				'contributors' => false,
				'short_description' => false,
				'description' => false,
				'sections' => false,
				'screenshots' => false,
				'tags' => false,
				'donate_link' => false,
				'ratings' => false,
			]
		]
	);


	if ( !empty( $plugin_information->versions ) || is_array( $plugin_information->versions ) ) {
	
		krsort( $plugin_information->versions );

		$rollback_versions = [];

		$current_index = 0;
		foreach ( $plugin_information->versions as $version => $download_link ) {
			if ( $max_versions <= $current_index ) {
				break;
			}

			// if ( version_compare( $version, ELEMENTOR_VERSION, '>=' ) ) {
			// 	continue;
			// }

			if ( 'trunk' === $version) {
				continue;
			}

			$current_index++;
			$rollback_versions[] = $version;
		}
		set_transient( $transient_key, $rollback_versions );
		// set_transient( 'elementor_rollback_versions_' . ELEMENTOR_VERSION, $rollback_versions, WEEK_IN_SECONDS );
	}else{
		set_transient( $transient_key, $plugin_information );
	}

}
