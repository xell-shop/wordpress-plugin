<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_getOrder($order_id)
{
    $order = wc_get_order( $order_id );

    $products = [];

    // Get and Loop Over Order Items
    foreach ( $order->get_items() as $item_id => $item ) {
        $product_id = $item->get_product_id();
        $variation_id = $item->get_variation_id();

        if($variation_id){
            $product = CWWYA_getProduct($variation_id);
        }else{
            $product = CWWYA_getProduct($product_id);
        }


        $products[] = array_merge(
            array(
                "product_id" => $product_id,
                "variation_id" => $variation_id,
                "quantity" => $item->get_quantity(),
            ),
            $product,
        );
    }
    return array(
        // Get Order ID and Key
        "id" => $order_id,
        "order_key" => $order->get_order_key(),
        
        // Get Order Totals $0.00
        "formatted_order_total" => $order->get_formatted_order_total(),
        "cart_tax" => $order->get_cart_tax(),
        "currency" => $order->get_currency(),
        "discount_tax" => $order->get_discount_tax(),
        "discount_to_display" => $order->get_discount_to_display(),
        "discount_total" => $order->get_discount_total(),
        "fees" => $order->get_fees(),
        "shipping_tax" => $order->get_shipping_tax(),
        "shipping_total" => $order->get_shipping_total(),
        "subtotal" => $order->get_subtotal(),
        "subtotal_to_display" => $order->get_subtotal_to_display(),
        "tax_totals" => $order->get_tax_totals(),
        "taxes" => $order->get_taxes(),
        "total" => $order->get_total(),
        "total_discount" => $order->get_total_discount(),
        "total_tax" => $order->get_total_tax(),
        "total_refunded" => $order->get_total_refunded(),
        "total_tax_refunded" => $order->get_total_tax_refunded(),
        "total_shipping_refunded" => $order->get_total_shipping_refunded(),
        "item_count_refunded" => $order->get_item_count_refunded(),
        "total_qty_refunded" => $order->get_total_qty_refunded(),
        "remaining_refund_amount" => $order->get_remaining_refund_amount(),

        //Products
        "products" => $products,
        
        // Get Order Shipping
        "shipping_method" => $order->get_shipping_method(),
        "shipping_methods" => $order->get_shipping_methods(),
        "shipping_to_display" => $order->get_shipping_to_display(),
        
        // Get Order Dates
        "date_created" => $order->get_date_created(),
        "date_modified" => $order->get_date_modified(),
        "date_completed" => $order->get_date_completed(),
        "date_paid" => $order->get_date_paid(),
        
        // Get Order User, Billing & Shipping Addresses
        "customer_id" => $order->get_customer_id(),
        "user_id" => $order->get_user_id(),
        // "user" => $order->get_user(),
        "customer_ip_address" => $order->get_customer_ip_address(),
        "customer_user_agent" => $order->get_customer_user_agent(),
        "created_via" => $order->get_created_via(),
        "customer_note" => $order->get_customer_note(),
        "billing_first_name" => $order->get_billing_first_name(),
        "billing_last_name" => $order->get_billing_last_name(),
        "billing_company" => $order->get_billing_company(),
        "billing_address_1" => $order->get_billing_address_1(),
        "billing_address_2" => $order->get_billing_address_2(),
        "billing_city" => $order->get_billing_city(),
        "billing_state" => $order->get_billing_state(),
        "billing_postcode" => $order->get_billing_postcode(),
        "billing_country" => $order->get_billing_country(),
        "billing_email" => $order->get_billing_email(),
        "billing_phone" => $order->get_billing_phone(),
        "shipping_first_name" => $order->get_shipping_first_name(),
        "shipping_last_name" => $order->get_shipping_last_name(),
        "shipping_company" => $order->get_shipping_company(),
        "shipping_address_1" => $order->get_shipping_address_1(),
        "shipping_address_2" => $order->get_shipping_address_2(),
        "shipping_city" => $order->get_shipping_city(),
        "shipping_state" => $order->get_shipping_state(),
        "shipping_postcode" => $order->get_shipping_postcode(),
        "shipping_country" => $order->get_shipping_country(),
        "address" => $order->get_address(),
        "shipping_address_map_url" => $order->get_shipping_address_map_url(),
        "formatted_billing_full_name" => $order->get_formatted_billing_full_name(),
        "formatted_shipping_full_name" => $order->get_formatted_shipping_full_name(),
        "formatted_billing_address" => $order->get_formatted_billing_address(),
        "formatted_shipping_address" => $order->get_formatted_shipping_address(),
        
        // Get Order Payment Details
        "payment_method" => $order->get_payment_method(),
        "payment_method_title" => $order->get_payment_method_title(),
        "transaction_id" => $order->get_transaction_id(),
        
        // Get Order URLs
        "checkout_payment_url" => $order->get_checkout_payment_url(),
        "checkout_order_received_url" => $order->get_checkout_order_received_url(),
        "cancel_order_url" => $order->get_cancel_order_url(),
        "cancel_order_url_raw" => $order->get_cancel_order_url_raw(),
        "cancel_endpoint" => $order->get_cancel_endpoint(),
        "view_order_url" => $order->get_view_order_url(),
        "edit_order_url" => $order->get_edit_order_url(),
        
        // Get Order Status
        "status" => $order->get_status(),
        
        // Get Thank You Page URL
        "checkout_order_received_url" => $order->get_checkout_order_received_url(),
    );
}
function CWWYA_getOrders()
{
    $query = new WC_Order_Query( array(
        'return' => 'ids',
    ) );
    $all_ids = $query->get_orders();
    
    $orders = [];
    foreach ( $all_ids as $id ) {
        try {
            $orders[] = CWWYA_getOrder($id);
        } catch (\Throwable $th) {
            
            $orders[] = $tr;
        }
    }
    return $orders;
}