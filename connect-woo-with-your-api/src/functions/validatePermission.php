<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_validatePermission($api,$permission)
{
    if($api["permission"] == NULL){
        return false;
    }  
    if($api["permission"][$permission] == NULL){
        return false;
    }   
    if($api["permission"][$permission] == false){
        return false;
    }   

    return true;
}