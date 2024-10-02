<?php 


if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_getGET(){
    $post = CWWYA_sanitizeObj(array("data"=>$_GET));
    return $post["data"];
}