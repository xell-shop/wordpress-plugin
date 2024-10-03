<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_validateConfigActive()
{
    $defaults = CWWYA_getConfigDefault();
    $configDefault = $defaults["configDefault"];
    
    $config = CWWYA_get_option("config");
    $config = CWWYA_joinArrayObject($configDefault,$config);
    
    if($config["active"] === false){
        throw new Exception('Config not active');
    }
}