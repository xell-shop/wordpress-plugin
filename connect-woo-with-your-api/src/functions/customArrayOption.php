<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_get_option($id = "option")
{
    $CWWYA_option = get_option("CWWYA_".$id);
    if($CWWYA_option === false || $CWWYA_option == null || $CWWYA_option == ""){
        $CWWYA_option = "[]";
    }
    $CWWYA_option = json_decode($CWWYA_option,true);
    return $CWWYA_option;
}

function CWWYA_set_option($id,$newItem)
{
    update_option("CWWYA_".$id,wp_json_encode($newItem));
}
function CWWYA_put_option($id,$newItem)
{
    $CWWYA_option = CWWYA_get_option($id);
    $CWWYA_option[] = array(
        "date" => gmdate("c"),
        "data" => $newItem,
    );
    update_option("CWWYA_".$id,wp_json_encode($CWWYA_option));
}