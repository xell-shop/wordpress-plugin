<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_setShippingMethodOrder($shipping_method,$order){
    $shipping_taxes = WC_Tax::calc_shipping_tax($shipping_method["total"], WC_Tax::get_shipping_tax_rates());
    $rate = new WC_Shipping_Rate(
        $shipping_method["method_id"], 
        $shipping_method["method_title"],
        $shipping_method["total"], 
        $shipping_taxes, 
        'flat_rate'
    );
    $item = new WC_Order_Item_Shipping();
    $item->set_props(array(
        'method_title' => $rate->label, 
        'method_id' => $rate->id, 
        'total' => wc_format_decimal($rate->cost), 
        'taxes' => $rate->taxes, 
        'meta_data' => $rate->get_meta_data()
        )
    );
    $order->remove_order_items("shipping");
    $order->add_item($item);
}
function CWWYA_setPaymentMethodOrder($payment_method,$order){
    $payment_gateways = WC()->payment_gateways->payment_gateways();
    $order->set_payment_method($payment_gateways[$payment_method]);
}
function CWWYA_validate_postOrderBilling($billing)
{
    $result = CWWYA_validateFieldRequired([
        "first_name",
        "last_name",
        "company",
        "email",
        "phone",
        "address_1",
        "country",
        "state",
        "city",
        "postcode",
    ],$billing);
    if( $result["status"] == 400 ){
        throw new Exception('Billing '.$result["data"]);
    }
}
function CWWYA_validate_postOrderShipping($shipping)
{
    $result = CWWYA_validateFieldRequired([
        "first_name",
        "last_name",
        "company",
        "address_1",
        "country",
        "state",
        "city",
        "postcode",
    ],$shipping);
    if( $result["status"] == 400 ){
        throw new Exception('Shipping '.$result["data"]);
    }
}
function CWWYA_validate_postOrderProduct($product)
{
    if( empty($product["id"]) ) {
        throw new Exception('Product id Required');
    }
    $p = wc_get_product( $product["id"] );
    if($p === false){
        throw new Exception('Product id ['.$product["id"].'] invalid');
    }
    if( empty($product["quantity"]) ) {
        throw new Exception('Product quantity Required');
    }
}

function CWWYA_validate_statusOrder($status){
    $orderStatus = array_keys( wc_get_order_statuses() );
    if(!in_array($status,$orderStatus)){
        throw new Exception('Order Status Invalid, permitted status: ['.join(" , ",$orderStatus)."]");
    }
}
function CWWYA_validate_shipping_methodOrder($shipping_method){
    $result = CWWYA_validateFieldRequired([
        "method_title",
        "method_id",
        "total",
    ],$shipping_method);
    if( $result["status"] == 400 ){
        throw new Exception('Shipping Method '.$result["data"]);
    }
}
function CWWYA_validate_payment_methodOrder($payment_method){
    $payment_gateways = array_keys(WC()->payment_gateways->get_available_payment_gateways());

    if(!in_array($payment_method,$payment_gateways)){
        throw new Exception('Payment invalid, list of payments [ '.join(" , ",$payment_gateways).' ]');
    }
}
function CWWYA_validate_postOrder($order)
{
    if( empty($order["status"]) ) {
        throw new Exception('Order Status Required');
    }
    CWWYA_validate_statusOrder($order["status"]);

    if( empty($order["products"]) ) {
        throw new Exception('Order Products Required');
    }
    if( !is_array($order["products"]) ) {
        throw new Exception('Order Products is not array');
    }
    if( count($order["products"]) == 0 ) {
        throw new Exception('Order Products is not array');
    }
    for ($i=0; $i < count($order["products"]); $i++) { 
        CWWYA_validate_postOrderProduct($order["products"][$i]);
    }
    if(empty($order["user_id"])){
        if( empty($order["billing"]) ) {
            throw new Exception('Order Billing Required');
        }
        if( !is_array($order["billing"]) ) {
            throw new Exception('Order Billing is not array');
        }
        CWWYA_validate_postOrderBilling($order["billing"]);
        if(!empty($order["shipping"]) ) {
            CWWYA_validate_postOrderShipping($order["shipping"]);
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
    }
    if(!empty($order["shipping_method"])){
        CWWYA_validate_shipping_methodOrder($order["shipping_method"]);
    }
    if(!empty($order["payment_method"])){
        CWWYA_validate_payment_methodOrder($order["payment_method"]);
    }
    return $order;
}
function CWWYA_validate_postOrders($orders)
{
    if( empty($orders) ) {
        throw new Exception('Orders Required');
    }
    if( !is_array($orders) ) {
        throw new Exception('Orders is not array');
    }
    if( count($orders) == 0 ) {
        throw new Exception('Orders is not array');
    }
}

function CWWYA_postOrder($order)
{
    try {
        $order = CWWYA_validate_postOrder($order);
        $newOrder = wc_create_order();
        for ($i=0; $i < count($order["products"]); $i++) { 
            $product = $order["products"][$i];
            $newOrder->add_product( get_product($product["id"]) , $product["quantity"] );
        }
        $newOrder->set_address( $order["billing"] , 'billing' );
        
        if($order["shipping"]){
            $newOrder->set_address( $order["shipping"] , 'shipping' );
        }
        if(!empty($order["shipping_method"])){
            CWWYA_setShippingMethodOrder($order["shipping_method"],$newOrder);
        }
        if(!empty($order["payment_method"])){
            CWWYA_setPaymentMethodOrder($order["payment_method"],$newOrder);
        }
        $newOrder->calculate_shipping();
        $newOrder->calculate_totals();

 
        $newOrder->save();
        $order_id = $newOrder->get_id();
        if($order["custom_meta"] && !empty($order["custom_meta"])){
            foreach ($order["custom_meta"] as $key => $value) {
                update_post_meta($order_id,$key,$value);
            }
        }

        try {
            $newOrder->update_status($order["status"]); 
        } catch (\Throwable $th) {
            //throw $th;
        }
        
 
        $newOrder->save();


        if($order["user_id"]){
            update_post_meta($order_id, '_customer_user', $order["user_id"]);
        }
        if($order["custom_meta"] && !empty($order["custom_meta"])){
            foreach ($order["custom_meta"] as $key => $value) {
                update_post_meta($order_id,$key,$value);
            }
        }
        
        return array(
            "status" => 200,
            "order" => CWWYA_getOrder($order_id), 
        );
    } catch (Exception $e) {
        return array(
            "status" => 400,
            "data" => $e->getMessage(),
        );
    }
}
function CWWYA_postOrders($data)
{
    $newOrders = $data["orders"];
    CWWYA_validate_postOrders($newOrders);
    $orders = [];
    for ($i=0; $i < count($newOrders); $i++) { 
        $orders[] = CWWYA_postOrder($newOrders[$i]);
    }

    return $orders;
}