<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_addTerm($term,$term_name)
{
    $term_id = [];
    for ($i=0; $i < count($term); $i++) { 
        $newTerm = wp_insert_term( $term[$i], $term_name);
        $newTerm = json_decode(wp_json_encode($newTerm),true);
        if($newTerm["error_data"]){
            $term_id[] = $newTerm["error_data"]["term_exists"];
        }else{
            $term_id[] = $newTerm["term_id"];
        }
    }
    return $term_id;
}