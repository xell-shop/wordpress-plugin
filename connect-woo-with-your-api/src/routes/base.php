<?php
require_once(preg_replace('/wp-content.*$/','',__DIR__).'wp-load.php');

$data = json_decode(file_get_contents('php://input'), true);
header('Content-Type: application/json; charset=utf-8');
if(isset($data)){
    $_POST = $data;
}
$token = $_POST["token"];

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

    echo json_encode(array(
        "status" => 200,
        "data" => $result,
    ));
} catch (Exception $e) {
    echo json_encode(array(
        "status" => 400,
        "data" => $e->getMessage()
    ));
}