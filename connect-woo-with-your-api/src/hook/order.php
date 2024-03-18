<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_sendHookOrder($type,$order_id)
{
    $order = CWWYA_getOrder($order_id);
    CWWYA_send_all_apis($type,$order);
}

function CWWYA_onChangeOrder($order_id, $order, $update)
{
    if ($update !== true) {
        CWWYA_sendHookOrder("order_create",$order_id);
    }else{
        CWWYA_sendHookOrder("order_update",$order_id);
    }
}
add_action('save_post_shop_order', 'CWWYA_onChangeOrder', 10, 3);


function CWWYA_onDeleteOrder($order_id)
{
    $post_type = get_post_type($order_id);
    if ($post_type !== 'shop_order') {
        return;
    }
    CWWYA_sendHookOrder("order_delete",$order_id);
}
add_action('before_delete_post', 'CWWYA_onDeleteOrder', 10, 1);