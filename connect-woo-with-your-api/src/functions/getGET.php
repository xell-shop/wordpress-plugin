<?php 


if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_getGET(){
    $post = json_decode(sanitize_text_field(wp_json_encode(array(
        "data"=>$_GET
    ))));
    return $post["data"];
}