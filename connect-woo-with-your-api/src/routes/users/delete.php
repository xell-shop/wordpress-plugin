<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_user_delete() {
    $permission = "user_delete";
    $run = "CWWYA_deleteUsers";
    return CWWYA_router_base($permission,$run);
}

function CWWYA_on_load_router_user_delete()
{
    register_rest_route( 'cwwya', 'users/delete', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_user_delete',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_user_delete' );
