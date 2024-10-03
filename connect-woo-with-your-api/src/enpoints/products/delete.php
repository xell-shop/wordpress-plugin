<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_deleteProduct($product_id)
{
    $product = wc_get_product( $product_id );
    if($product === false){
        return array(
            "status" => 400,
            "data" => "Product id [".$product_id."] Not Exist"
        );
    }
    wp_delete_post( $product_id );

    return array(
        "status" => 200,
        "product_delete" => $product_id
    );

}

function CWWYA_deleteProducts($data)
{
    
    $product_id = $data["product_id"];
    if( empty( $product_id) ) {
        throw new Exception('Product id Required');
    }
    $result = CWWYA_deleteProduct($product_id);
    return $result;
}