<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_getApiToken($token){
    if($token == null || $token == ""){
        return null;
    }

	$apis = CWWYA_get_option("apis");
    for ($i=0; $i < count($apis) ; $i++) { 
        $api = $apis[$i];
        if($api["token"]==$token){
            return $api;
        }
    }
    return null;
}