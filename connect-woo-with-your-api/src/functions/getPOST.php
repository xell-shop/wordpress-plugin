<?php 


if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_getPOST($key = null){
    try{
        //$_POST It is necesary for get all data in api body
        $pos = $_POST;
        if($key == null){
            $pos = $_POST[$key];
        }
        return CWWYA_sanitizeObj($pos);
    } catch (\Throwable $th) {
        return [];
    }
}