<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_order_create() {
    $permission = "order_create";
    $run = "CWWYA_postOrders";
    return CWWYA_router_base($permission,$run);
}

function CWWYA_on_load_router_order_create()
{
    register_rest_route( 'cwwya', 'orders/create', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_order_create',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_order_create' );