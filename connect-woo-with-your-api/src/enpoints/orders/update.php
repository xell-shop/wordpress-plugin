<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_validate_putOrder($order)
{
    $id = $order["id"];
    if( empty( $id) ) {
        throw new Exception('order id Required');
    }
}
function CWWYA_putOrder($order)
{
    try {
        CWWYA_validate_putOrder($order);
        $order_id = $order["id"];
        $orderOld =  wc_get_order( $order_id );

        if($orderOld === false){
            throw new Exception('order id Invalid');
        }

        if(!empty($order["status"])){
            CWWYA_validate_statusOrder($order["status"]);
            try {
                $orderOld->update_status($order["status"]); 
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        if(empty($order["user_id"])){
            if(!empty($order["billing"])){
                CWWYA_validate_postOrderBilling($order["billing"]);
                $orderOld->set_address( $order["billing"] , 'billing' );
            }
            if(!empty($order["shipping"])){
                CWWYA_validate_postOrderShipping($order["shipping"]);
                $orderOld->set_address( $order["shipping"] , 'shipping' );
            }
        }else{
            $user_id = $order["user_id"];
            $user = CWWYA_getUser($user_id);
            
            $order["billing"] = array(
                'first_name' => $user["billing_first_name"],
                'last_name'  => $user["billing_last_name"],
                'company'    => $user["billing_company"],
                'email'      => $user["user_email"],
                'phone'      => $user["billing_phone"],
                'address_1'  => $user["billing_address_1"],
                'address_2'  => $user["billing_address_2"],
                'city'       => $user["billing_city"],
                'state'      => $user["billing_state"],
                'postcode'   => $user["billing_postcode"],
                'country'    => $user["billing_country"],
            );
            $order["shipping"] = array(
                'first_name' => $user["shipping_first_name"],
                'last_name'  => $user["shipping_last_name"],
                'company'    => $user["shipping_company"],
                'address_1'  => $user["shipping_address_1"],
                'address_2'  => $user["shipping_address_2"],
                'city'       => $user["shipping_city"],
                'state'      => $user["shipping_state"],
                'postcode'   => $user["shipping_postcode"],
                'country'    => $user["shipping_country"],
            );
            $orderOld->set_address( $order["billing"] , 'billing' );
            $orderOld->set_address( $order["shipping"] , 'shipping' );
        }
        if(!empty($order["shipping_method"])){
            CWWYA_validate_shipping_methodOrder($order["shipping_method"]);
            CWWYA_setShippingMethodOrder($order["shipping_method"],$orderOld);
        }
        if(!empty($order["payment_method"])){
            CWWYA_validate_payment_methodOrder($order["payment_method"]);
            CWWYA_setPaymentMethodOrder($order["payment_method"],$orderOld);
        }
        
        $orderOld->calculate_shipping();
        $orderOld->calculate_totals();
        $orderOld->save();
        return array(
            "status" => 200,
            "data" => "Order update [".$order_id."]"
        );
    } catch (Exception $e) {
        return array(
            "status" => 400,
            "data" => $e->getMessage(),
        );
    }
}

function CWWYA_putOrders($data)
{
    $newOrders = $data["orders"];
    CWWYA_validate_postOrders($newOrders);
    $orders = [];
    for ($i=0; $i < count($newOrders); $i++) { 
        $orders[] = CWWYA_putOrder($newOrders[$i]);
    }

    return $orders;
}