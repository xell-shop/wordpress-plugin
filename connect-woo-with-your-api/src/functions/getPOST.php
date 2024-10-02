<?php 


if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_getPOST($key){
    try{
        return CWWYA_sanitizeObj($_POST[$key]);
    } catch (\Throwable $th) {
        return [];
    }
}