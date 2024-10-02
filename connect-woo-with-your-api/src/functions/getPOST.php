<?php 


if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_getPOST(){
    try{
        return CWWYA_sanitizeObj($_POST);
    } catch (\Throwable $th) {
        return [];
    }
}