<?php 


if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_sanitizeObj($obj){
    $OBJ = array("obj"=>$obj);
    return json_decode(sanitize_text_field(wp_json_encode($OBJ)),true)["obj"];
}