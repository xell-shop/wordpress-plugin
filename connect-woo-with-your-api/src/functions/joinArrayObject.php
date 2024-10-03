<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_joinArrayObject($a,$b)
{
    foreach ($a as $key => $value) {
        if(array_key_exists($key,$b)){
            $a[$key] = $b[$key];
        }
    }
    return $a;
}
