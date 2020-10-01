<?php
namespace IqbalRony;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Version {

    /**
	 * Switch version task apply
	 */
    public static function switch_version_apply(){

		if ( !current_user_can( 'activate_plugins' ) || !current_user_can( 'delete_plugins' ) ) {
			return;
        }

		if ( empty( $_POST['slug'] ) || empty( $_POST['version'] ) ||  empty( $_POST['wpvs_submit'] ) ||  empty( $_POST['wpvs_nonce'] ) ) {
			return;
		}
        
		if ( $_POST['wpvs_submit'] !== 'submit' || !wp_verify_nonce( $_POST['wpvs_nonce'], 'wpvs_version_switcher' ) ) {
			return;
		}

		
		$all_version = get_transient( wpvs_get_key( $_POST['slug'] ) );
		if( is_array($all_version) && !in_array( $_POST['version'], $all_version) ){
			return;
		}
		
		Version::version_switch( $_POST['slug'], $_POST['version'] );

    }

	 /**
	 * Version Switch
	 */
	public static function version_switch($plugin_slug,$version) {
        
		if ( empty( $version )  ) {
			wp_die( __( 'Error occurred, The version selected is invalid. Try selecting different version.', 'version-switcher' ) );
		}

		$switch = new Switcher(
			[
				'version' => $version,
				'plugin_name' => $plugin_slug,
				'plugin_slug' => $plugin_slug,
				'package_url' => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, $version ),
			]
		);

		$switch->run();

		wp_die(
			'', __( 'Rollback to Previous Version', 'version-switcher' ), [
				'response' => 200,
			]
		);
	}



	 /**
	 * Save all cache key
	 */
	public static function save_all_cache_key($key){

        $all_keys = get_transient( wpvs_get_key( 'cache_key' ) );

        if ( false === $all_keys ) {

            $added_key = [$key];

            set_transient( wpvs_get_key( 'cache_key' ), $added_key );

        } elseif( is_array($all_keys) && !in_array( $key, $all_keys) ){

            $old_array = $all_keys;
            $new_array = [$key];
            
            $added_plugin = array_merge( $old_array, $new_array );
            set_transient( wpvs_get_key( 'cache_key' ), $added_plugin );
        }

    }
    
    
    /**
	 * Save version to transient.
	 */
    public static function save_version_data ( $plugin_slug, $transient_key, $return = false, $max_versions =100000 ){
    
        if ( ! function_exists( 'plugins_api' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }

        self::save_all_cache_key( $transient_key );
    
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

                if ( 'trunk' === $version) {
                    continue;
                }
    
                $current_index++;
                $rollback_versions[] = $version;
            }
            set_transient( $transient_key, $rollback_versions );
        }else{
            set_transient( $transient_key, $plugin_information );
        }

        if( $return && $plugin_information ){
            return $plugin_information;
        }
    
    }


    /**
    * Get Installed Plugin Info
    */
   public static function get_all_installed_plugin(){

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



    /**
    * Get update plugin info
    */
   public static function get_updates_plugin(){

       if ( ! function_exists( 'get_plugins' ) ) {
           require_once ABSPATH . 'wp-admin/includes/plugin.php';
       }

       $update_info = get_plugin_updates();
       $update_vs = [];
    
        foreach ( (array) $update_info as $key => $object ) {
            $slug = str_replace( "-", "_", $object->update->slug );
            $update_vs[ $slug ] = $object->update->new_version ;
        }

       return $update_vs;
   }


    /**
    * Check is update version ixist in cache
    */
   public static function is_version_exist_in_cache( array $all_versions, $key ){

       $update_info = self::get_updates_plugin();
       return in_array( $update_info[$key], $all_versions );
   }

}