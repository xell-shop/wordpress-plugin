<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_order_delete(WP_REST_Request $request) {
    $permission = "order_delete";
    $run = "CWWYA_deleteOrders";
    $data = (json_decode($request->get_body(), true));
    return CWWYA_router_base($permission,$run,$data);
}

function CWWYA_on_load_router_order_delete()
{
    register_rest_route( CWWYA_RUTE, 'orders/delete', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_order_delete',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_order_delete' );
