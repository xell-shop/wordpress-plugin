<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_user_view() {
    $permission = "user_ready";
    $run = "CWWYA_getUsers";
    return CWWYA_router_base($permission,$run);
}

function CWWYA_on_load_router_user_view()
{
    register_rest_route( 'cwwya', 'users/view', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_user_view',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_user_view' );
