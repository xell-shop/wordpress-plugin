<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_product_create() {
    $permission = "product_create";
    $run = "CWWYA_postProducts";
    return CWWYA_router_base($permission,$run);
}

function CWWYA_on_load_router_product_create()
{
    register_rest_route( 'cwwya', 'products/create', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_product_create',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_product_create' );