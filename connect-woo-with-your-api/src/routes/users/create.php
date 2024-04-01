<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_user_create() {
    $permission = "user_create";
    $run = "CWWYA_postUsers";
    return CWWYA_router_base($permission,$run);
}

function CWWYA_on_load_router_user_create()
{
    register_rest_route( 'cwwya', 'users/create', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_user_create',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_user_create' );