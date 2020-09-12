<?php
namespace IqbalRony;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Version {

	 /**
	 * Version Switch
	 */
	public static function version_switch($plugin_slug,$version) {
		// check_admin_referer( 'wpvs_switcher' );
        $transient_key = wpvs_get_key( $plugin_slug );
        $all_versions = get_transient( $transient_key );
        
		if ( empty( $version )  ) {
			wp_die( __( 'Error occurred, The version selected is invalid. Try selecting different version.', 'elementor' ) );
		}

		// $plugin_slug = basename( ELEMENTOR__FILE__, '.php' );

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
			'', __( 'Rollback to Previous Version', 'elementor' ), [
				'response' => 200,
			]
		);
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
	 * Get update plugin Info
	 */
	public static function gest_all_installed_plugin(){
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
	 * Save all Plugin version
	 */
    public static function save_all_plugin_versions() {
    
        $all_plugins = self::get_all_installed_plugin();

        if( !function_exists('plugins_api') ){
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }
    
        foreach ( $all_plugins as $key => $value ) {
    
            $transient_key = wpvs_get_key( $value['plugin_slug'] );
    
            $get_versions = get_transient( $transient_key );

            // echo '<pre>';
            // var_dump($value['plugin_slug']);
            // var_dump($get_versions);
            // echo '</pre>';

            if ( false === $get_versions ) {    
                self::save_version_data ($value['plugin_slug'],$transient_key);

            }
        }
    }
    
    
    /**
	 * Save version to transient.
	 */
    public static function save_version_data ($plugin_slug,$transient_key,$return = false,$max_versions =100000){
    
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
        }else{
            set_transient( $transient_key, $plugin_information );
        }

        if( $return && $plugin_information ){
            return $plugin_information;
        }
    
    }




}