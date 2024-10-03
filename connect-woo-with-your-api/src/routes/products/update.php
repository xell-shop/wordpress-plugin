<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_product_update(WP_REST_Request $request) {
    $permission = "product_update";
    $run = "CWWYA_putProducts";
    $data = CWWYA_sanitizeObj(json_decode($request->get_body(), true));
    return CWWYA_router_base($permission,$run,$data);
}

function CWWYA_on_load_router_product_update()
{
    register_rest_route( 'cwwya', 'products/update', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_product_update',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_product_update' );
