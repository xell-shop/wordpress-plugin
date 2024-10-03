<?php

if ( ! defined( 'ABSPATH' ) ) exit;

define("CWWYA_RUTE","cwwya");

if (!function_exists( 'is_plugin_active' ))
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
if(!is_plugin_active( 'woocommerce/woocommerce.php' )){
    function CWWYA_log_dependencia() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>
            XELL requiere the plugin  "Woocommerce"
            </p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'CWWYA_log_dependencia' );
}else{
    if ( is_callable('curl_init') && 
        function_exists('curl_init') && 
        function_exists('curl_close') && 
        function_exists('curl_exec') && 
        function_exists('curl_setopt_array')
    ){

        function CWWYA_get_version() {
            $plugin_data = get_plugin_data( __FILE__ );
            $plugin_version = $plugin_data['Version'];
            return $plugin_version;
        }


        define("CWWYA_LOG",false);
        define("CWWYA_PATH",plugin_dir_path(__FILE__));
        define("CWWYA_URL",plugin_dir_url(__FILE__));
        
        require_once CWWYA_PATH . "src/_index.php";
    }else{
        function CWWYA_log_dependencia() {
            ?>
            <div class="notice notice-error is-dismissible">
                <p>
                XELL requiere "Curl"
                </p>
            </div>
            <?php
        }
        add_action( 'admin_notices', 'CWWYA_log_dependencia' );
    }
}