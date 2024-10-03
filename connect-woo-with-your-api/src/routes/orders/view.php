<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_order_view(WP_REST_Request $request) {
    $permission = "order_ready";
    $run = "CWWYA_getOrders";
    $data = (json_decode($request->get_body(), true));
    return CWWYA_router_base($permission,$run,$data);
}

function CWWYA_on_load_router_order_view()
{
    register_rest_route( CWWYA_RUTE, 'orders/view', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_order_view',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_order_view' );
