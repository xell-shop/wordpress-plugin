<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_order_view() {
    $permission = "order_ready";
    $run = "CWWYA_getOrders";
    return CWWYA_router_base($permission,$run);
}

function CWWYA_on_load_router_order_view()
{
    register_rest_route( 'cwwya', 'orders/view', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_order_view',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_order_view' );
