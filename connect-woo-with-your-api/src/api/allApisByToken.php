<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_send_all_apis($type,$data)
{
	$apis = CWWYA_get_option("apis");
    $results = [];
    for ($i=0; $i < count($apis); $i++) { 
        $api = $apis[$i];
        if(CWWYA_validatePermission($api,$type)){
            $api = new CWWYA_api($api);
            $results[] = $api->send($type,$data);
        }else {
            $results[] = array(
                "status" => 400,
                "data" => 'Permission denied'
            );
        }
    }
    return $results;
}