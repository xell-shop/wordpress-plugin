<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function CWWYA_router_product_delete() {
    $permission = "product_delete";
    $run = "CWWYA_deleteProducts";
    return CWWYA_router_base($permission,$run);
}

function CWWYA_on_load_router_product_delete()
{
    register_rest_route( 'cwwya', 'products/delete', array(
      'methods' => 'POST',
      'callback' => 'CWWYA_router_product_delete',
    ) );
}

add_action( 'rest_api_init', 'CWWYA_on_load_router_product_delete' );
