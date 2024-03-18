<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_sendHookProduct($type,$product_id)
{
    $product = CWWYA_getProduct($product_id);
    CWWYA_send_all_apis($type,$product);
}

function CWWYA_onCreateProduct($product_id)
{
    CWWYA_sendHookProduct("product_create",$product_id);
}
add_action('woocommerce_new_product', 'CWWYA_onCreateProduct', 10, 1);

function CWWYA_onUpdateProduct($product_id)
{
    CWWYA_sendHookProduct("product_update",$product_id);
}
add_action('woocommerce_update_product', 'CWWYA_onUpdateProduct', 10, 2);

function CWWYA_onDeleteProduct($product_id)
{
    $post_type = get_post_type($product_id);
    if ($post_type !== 'product') {
        return;
    }
    CWWYA_sendHookProduct("product_delete",$product_id);
}
add_action('before_delete_post', 'CWWYA_onDeleteProduct', 10, 1);