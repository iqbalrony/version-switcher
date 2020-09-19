<?php
// namespace IqbalRony;
/**
 * Post Type Ajax Load More
 */

add_action('wp_ajax_wpvs_get_all_version', 'wpvs__ajax_handler_func');
function wpvs__ajax_handler_func() {

	$security = check_ajax_referer('wpvs_version_switcher', 'security');
	if (true == $security && !empty($_POST['plugin_slug'])) :

        $slug = $_POST['plugin_slug'];

        $transient_key = wpvs_get_key( $slug );

        $versions = get_transient( $transient_key );

        if ( false === $versions ) {
            IqbalRony\Version::save_version_data( $slug,  $transient_key, true );
            $versions = get_transient( $transient_key );
        }
        
        if( is_Array($versions) && !array_key_exists('errors',$versions) ){
            $need_cache_update = \IqbalRony\Version::is_version_exist_in_cache( $versions, str_replace( "-", "_", $slug ) );
            if( false == $need_cache_update ){
                IqbalRony\Version::save_version_data( $slug,  $transient_key, true );
                $versions = get_transient( $transient_key );
                
            }
        }

        $versions = $versions ? $versions : [];
        // echo '<pre>';
        // var_dump($versions);
        // echo '</pre>';
        if( !empty($versions) ){
            if( !array_key_exists('errors',$versions) ){
                $option_html = "<option value='' >".esc_html__( 'Select Version', '@version-switcher' )."</option>";
                foreach ( $versions as $version ) {
                    $option_html .= "<option value='{$version}'>$version</option>";
                }
                echo $option_html;
            }else{
                echo "<option value='' >".esc_html__( 'Plugin is not found in WordPress ORG', '@version-switcher' )."</option>";
            }
            
        }else{
            echo "<option value='' >".esc_html__( 'No Version Found', '@version-switcher' )."</option>";
        }
	endif;
	wp_die();
}
