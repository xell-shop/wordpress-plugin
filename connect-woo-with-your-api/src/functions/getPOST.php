<?php 


if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_getPOST($key){
    try{
        // $_POST It is necesary for get all data in api body
        return CWWYA_sanitizeObj($_POST[$key]);
    } catch (\Throwable $th) {
        return [];
    }
}