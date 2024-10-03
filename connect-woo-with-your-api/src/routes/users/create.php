<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_user_create(WP_REST_Request $request) {
    $permission = "user_create";
    $run = "CWWYA_postUsers";
    $data = (json_decode($request->get_body(), true));
    return CWWYA_router_base($permission,$run,$data);
}

function CWWYA_on_load_router_user_create()
{
    register_rest_route( CWWYA_RUTE, 'users/create', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_user_create',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_user_create' );