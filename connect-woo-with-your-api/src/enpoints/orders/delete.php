<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_deleteOrder($order_id)
{
    $order = wc_get_order( $order_id );
    if(!$order){
        throw new Exception("Order id [".$order_id."] Not Exist");
    }
    wp_delete_post($order_id,true);

    return array(
        "status" => 200,
        "order_delete" => $order_id
    );
}
function CWWYA_deleteOrders($data)
{
    $order_id = $data["order_id"];
    if( empty( $order_id) ) {
        throw new Exception('Order id Required');
    }
    $result = CWWYA_deleteOrder($order_id);
    return $result;
}