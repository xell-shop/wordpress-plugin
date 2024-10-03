<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function XELL_create_menu() {
	//create new top-level menu
	add_menu_page('XELL Settings', 'Xell', 'administrator', "XELL_config", 'XELL_settings_page' ,XELL_URL."src/img/icon.png" );

}
add_action('admin_menu', 'XELL_create_menu');



function XELL_saveApi($token)
{
    if($token  != "" && is_string($token)){
        $config = array(
            "name"          => "XELL",
            "url"           => XELL_URL_API,
            "token"         => $token,
            "permission"    => array(
                "product_ready"     => true,
                "product_create"    => true,
                "product_update"    => true,
                "product_delete"    => true,
                "order_ready"       => true,
                "order_create"      => true,
                "order_update"      => true,
                "order_delete"      => true,
                "user_ready"        => true,
                "user_create"       => true,
                "user_update"       => true,
                "user_delete"       => true,
            ),
        );
	    $apis = CWWYA_get_option("apis");
        for ($i=0; $i < count($apis); $i++) { 
            if($apis[$i]["name"] == "XELL"){
                CWWYA_setApiByName("XELL",$config);
                return;
            }
        }
        CWWYA_addApi($config);
        CWWYA_active();
    }
}
function XELL_getTokenApi(){
    $apis = CWWYA_get_option("apis");
    for ($i=0; $i < count($apis); $i++) { 
        if($apis[$i]["name"] == "XELL"){
            return $apis[$i]["token"] ;
        }
    }
    return "";
}

function XELL_settings_page() {
    $GET_token = sanitize_text_field($_GET["token"]);
    $GET_error = sanitize_text_field($_GET["error"]);
    $GET_disconnect = sanitize_text_field($_GET["disconnect"]);

    if($GET_token){
        XELL_saveApi($GET_token); 
        header("Location: ".menu_page_url("XELL_config",false));
        exit;
    }
    if($GET_error){
        CWWYA_deleteApiByName("XELL");
    }
    if($GET_disconnect){
        CWWYA_deleteApiByName("XELL");
    }
    $SERVER_https = sanitize_text_field($_SERVER['HTTPS']);
    $SERVER_host = sanitize_text_field($_SERVER['HTTP_HOST']);
    $SERVER_request_uri = sanitize_text_field($_SERVER['REQUEST_URI']);
    if(isset($SERVER_https) && $SERVER_https === 'on')   
         $url = "https://";   
    else  
         $url = "http://";   
    // Append the host(domain name, ip) to the URL.   
    $url.= $SERVER_host;   
    // Append the requested resource location to the URL   
    $url.= $SERVER_request_uri;    

    $token = XELL_getTokenApi();

    $name = get_bloginfo();

    ?>
    <div class="wrap page-config-XELL">
		<form method="get" class="form-login" action="<?php echo esc_attr(XELL_URL_CONNECT);?>">
            <input type="hidden" name="name" value="<?php echo esc_attr($name);?>">
            <input type="hidden" name="home" value="<?php echo esc_attr(get_home_url());?>">
            <input type="hidden" name="logo" value="<?php echo esc_attr(get_site_icon_url());?>">
            <input type="hidden" name="url" value="<?php echo esc_attr($url);?>">
            <input type="hidden" name="api" value="<?php echo esc_attr(CWWYA_URL);?>">

            <img class="logo" src="<?php echo esc_attr(XELL_URL."src/img/logo.png");?>" alt="">
            <?php
                if($GET_error){
                    ?>
                        <img class="status" src="<?php echo esc_attr(XELL_URL."src/img/x.png");?>" alt="">
                        <div class="msg-error msg-status"><?php echo esc_html($GET_error);?></div>
                    <?php
                }else{
                    if($token  != "" && is_string($token)){
                        ?>
                            <img class="status" src="<?php echo esc_attr( XELL_URL."src/img/ok.png");?>" alt="">
                            <div class="msg-ok msg-status">Xell Conectado</div>
                            <input type="hidden" name="disconnect" value="true">
                            <button class="btn-disconnect">Desconectar Xell</button>
                        <?php
                    }else{
                        ?>
                            <button class="btn-connect">Conectar Xell</button>
                        <?php
                    }
                }
            ?>
		</form>
    </div>
    <?php 
}