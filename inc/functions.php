<?php

/**
 * Get transient key
 */
function wpvs_get_key( $slug ){
	$transient_key = WPVS_KEY. str_replace( "-", "_", $slug );
	return $transient_key;
}


/**
 * check is curl request failed
 */
function is_curl_failed($data){	
	$rasult = false;
	if( is_object($data) && property_exists( $data, 'error_data' ) && !empty($data->error_data) ){
		$rasult = true;
	}
	return $rasult;
}


/**
 * Delete All cache
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


