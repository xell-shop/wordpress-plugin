<?php

function CWWYA_router_base($permission,$run,$data) {
    header('Content-Type: application/json; charset=utf-8');
    $token = $data["token"];

    try {
        CWWYA_validateConfigActive();
        $api = CWWYA_getApiToken($token);
        if($api == null){
            throw new Exception('Token invalid');
        }
        if(!CWWYA_validatePermission($api,$permission)){
            throw new Exception('Permission denied');
        }
        $result = $run($data);

        echo wp_json_encode(array(
            "status" => 200,
            "data" => $result,
        ));
    } catch (Exception $e) {
        echo wp_json_encode(array(
            "status" => 400,
            "data" => $e->getMessage()
        ));
    }
}