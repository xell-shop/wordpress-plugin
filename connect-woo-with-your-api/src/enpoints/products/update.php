<?php

function CWWYA_putProducts($data)
{
    $products = $data["products"];
    if( empty($products) ) {
        throw new Exception('Products Required');
    }
    if( !is_array($products) ) {
        throw new Exception('Products is not array');
    }
    if( count($products) == 0 ) {
        throw new Exception('Products is not array');
    }
    $result = [];
    for ($i=0; $i < count($products); $i++) { 
        $result[] = CWWYA_updateProduct($products[$i]);
    }
    return $result;
}