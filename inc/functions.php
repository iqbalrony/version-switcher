<?php

/**
 * Get transient key
 */
if ( ! function_exists( 'irvs_get_key' ) ) {
	function irvs_get_key( $slug ){
		$transient_key = IRVS_KEY. str_replace( "-", "_", $slug );
		return $transient_key;
	}
}


/**
 * check is curl request failed
 */
if ( ! function_exists( 'irvs_is_curl_failed' ) ) {
	function irvs_is_curl_failed( $versions ){	
		$rasult = false;
		if( is_object($versions ) && property_exists( $versions, 'error_data' ) && !empty($versions->error_data) ){
			$rasult = true;
		}
		return $rasult;
	}
}


/**
 * Delete All cache
 */
if ( ! function_exists( 'irvs_delete_all_cache' ) ) {
	function irvs_delete_all_cache(){
		
		$all_slugs = IqbalRony\VersionSwitcher\Version::get_all_installed_plugin();
		if( $all_slugs ){
			foreach ( $all_slugs as $key => $value ) {
				delete_transient( irvs_get_key( $value['plugin_slug'] ) );
			}
			delete_transient( irvs_get_key( 'cache_key' ) );
		}
	}
}


