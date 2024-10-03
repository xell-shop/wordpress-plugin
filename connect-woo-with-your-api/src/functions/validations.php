<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_validateFieldRequired($atts = [],$array)
{
    for ($i=0; $i < count($atts); $i++) { 
        if( empty( $array[$atts[$i]] ) ) {
            return array(
                "status" => 400,
                "data" => $atts[$i]." required"
            );
        }
    }
    return array(
        "status" => 200,
    );
}
function CWWYA_validateFieldDefault($atts = [],$array)
{
    for ($i=0; $i < count($atts); $i++) { 
        if( null ==  $array[$atts[$i]["key"]]  ) {
            $array[$atts[$i]["key"]] = $atts[$i]["value"];
        }
    }
    return $array;
}