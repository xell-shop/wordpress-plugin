<?php 


if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_getPOST(){
    try{

        return $_POST;
        
    } catch (\Throwable $th) {
        return [];
    }
}