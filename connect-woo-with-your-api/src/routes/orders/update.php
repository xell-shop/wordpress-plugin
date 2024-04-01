<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_order_update() {
    $permission = "order_update";
    $run = "CWWYA_putOrders";
    return CWWYA_router_base($permission,$run);
}

function CWWYA_on_load_router_order_update()
{
    register_rest_route( 'cwwya', 'orders/update', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_order_update',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_order_update' );
