<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_product_view() {
    $permission = "product_ready";
    $run = "CWWYA_getProducts";
    return CWWYA_router_base($permission,$run);
}

function CWWYA_on_load_router_product_view()
{
    register_rest_route( 'cwwya', 'products/view', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_product_view',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_product_view' );
