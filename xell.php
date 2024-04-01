<?php
/*
Plugin Name: Xell Shop
Plugin URI: https://gitlab.com/xell-shop/wordpress-plugin-connector
Description: Este conector posibilita la sincronización del inventario desde tu tienda en XELL directamente con esta plataforma de comercio virtual. De esta manera, puedes reflejar de forma precisa y en tiempo real el estado de tus existencias, asegurando una gestión eficiente y una experiencia de compra óptima para tus clientes.
Author: xell-shop
Version: 1.1.2
Author URI: https://xell.shop/
License: GPLv2 or later
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if (!function_exists( 'is_plugin_active' ))
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

if(!is_plugin_active( 'woocommerce/woocommerce.php' )){
    function XELL_log_dependencia() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>
                Xell requiere the plugin  "Woocommerce"
            </p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'XELL_log_dependencia' );
}else{
   
    function XELL_get_version() {
        $plugin_data = get_plugin_data( __FILE__ );
        $plugin_version = $plugin_data['Version'];
        return $plugin_version;
    }



    define("XELL_LOG",false);
    define("XELL_PATH",plugin_dir_path(__FILE__));
    define("XELL_URL",plugin_dir_url(__FILE__));
    define("XELL_URL_API","http://api.beta.xell.shop");
    define("XELL_URL_CONNECT","https://beta.xell.shop/configuraciones/cms/wordpress/connect/api");

    if(!is_plugin_active( 'connect-woocommerce-with-your-api/connect-woocommerce-with-your-api.php' ) && !is_plugin_active( 'connect-woocommerce-with-your-api-master/connect-woocommerce-with-your-api.php' )){
    
        require_once XELL_PATH . "connect-woo-with-your-api/connect-woo-with-your-api.php";
    }

    require_once XELL_PATH . "src/_index.php";
}
