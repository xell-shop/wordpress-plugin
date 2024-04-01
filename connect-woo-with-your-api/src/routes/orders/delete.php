<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_order_delete() {
    $permission = "order_delete";
    $run = "CWWYA_deleteOrders";
    return CWWYA_router_base($permission,$run);
}

function CWWYA_on_load_router_order_delete()
{
    register_rest_route( 'cwwya', 'orders/delete', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_order_delete',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_order_delete' );
