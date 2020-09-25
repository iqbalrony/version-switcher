<?php


/* 
	Get transient key
*/
function wpvs_get_key( $slug ){
	$transient_key = WPVS_KEY. str_replace( "-", "_", $slug );
	return $transient_key;
}

/* 
	check is curl request failed
*/
function is_curl_failed($data){	
	$rasult = false;
	if( is_object($data) && property_exists( $data, 'error_data' ) && !empty($data->error_data) ){
		$rasult = true;
	}
	return $rasult;
}

/* 
	Delete All cache
*/
function wpvs_delete_all_cache(){
	
	$all_slugs = IqbalRony\Version::get_all_installed_plugin();
	if( $all_slugs ){
		foreach ( $all_slugs as $key => $value ) {
			delete_transient( wpvs_get_key( $value['plugin_slug'] ) );
		}
		delete_transient( wpvs_get_key( 'cache_key' ) );
	}
}





/* 
this is functions for test pourpose
*/
function wpvs_check_all_cache3(){
	
	$all_keys = get_transient( wpvs_get_key( 'cache_key' ) );
	
	echo '<pre>';
	var_dump( $all_keys );
	echo '</pre>';
}
// add_action('wp_footer','wpvs_check_all_cache3');

function wpvs_check_all_cache(){
	
	$all_slugs = IqbalRony\Version::get_all_installed_plugin();
	if( $all_slugs ){
		foreach ( $all_slugs as $key => $value ) {
			// delete_transient( wpvs_get_key( $value['plugin_slug'] ) );
			$all_version = get_transient(  wpvs_get_key( $value['plugin_slug'] ) );
			echo '<pre>';
			var_dump( $value['name'] );
			var_dump( is_curl_failed($all_version) );
			var_dump( $all_version );
			echo '</pre>';
		}
	}
}
// add_action('wp_footer','wpvs_check_all_cache');

