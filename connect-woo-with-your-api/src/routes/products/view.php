<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_product_view(WP_REST_Request $request) {
    $permission = "product_ready";
    $run = "CWWYA_getProducts";
    $data = (json_decode($request->get_body(), true));
    return CWWYA_router_base($permission,$run,$data);
}

function CWWYA_on_load_router_product_view()
{
    register_rest_route( CWWYA_RUTE, 'products/view', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_product_view',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_product_view' );
