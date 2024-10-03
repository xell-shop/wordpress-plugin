<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_user_view(WP_REST_Request $request) {
    $permission = "user_ready";
    $run = "CWWYA_getUsers";
    $data = CWWYA_sanitizeObj(json_decode($request->get_body(), true));
    return CWWYA_router_base($permission,$run,$data);
}

function CWWYA_on_load_router_user_view()
{
    register_rest_route( 'cwwya', 'users/view', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_user_view',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_user_view' );
