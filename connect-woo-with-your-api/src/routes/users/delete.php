<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_user_delete(WP_REST_Request $request) {
    $permission = "user_delete";
    $run = "CWWYA_deleteUsers";
    $data = (json_decode($request->get_body(), true));
    return CWWYA_router_base($permission,$run,$data);
}

function CWWYA_on_load_router_user_delete()
{
    register_rest_route( CWWYA_RUTE, 'users/delete', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_user_delete',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_user_delete' );
