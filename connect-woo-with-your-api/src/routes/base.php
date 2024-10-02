<?php

function CWWYA_router_base($permission,$run) {
    //file_get_contents It is necesary for get all data in api body
    $data = CWWYA_sanitizeObj(json_decode(file_get_contents('php://input'), true));
    header('Content-Type: application/json; charset=utf-8');
    if(isset($data)){
        $_POST = $data;
    }
    $token = CWWYA_getPOST("token");

    try {
        CWWYA_validateConfigActive();
        $api = CWWYA_getApiToken($token);
        if($api == null){
            throw new Exception('Token invalid');
        }
        if(!CWWYA_validatePermission($api,$permission)){
            throw new Exception('Permission denied');
        }
        $result = $run();

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