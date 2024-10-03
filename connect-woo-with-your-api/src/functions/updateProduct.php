<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_updateProduct($product,$newProductWoo = null)
{
    if($newProductWoo == null){
        $result = CWWYA_validateFieldRequired(array(
            "id",
        ),$product);
        if($result["status"] != 200){
            return $result;
        }
        $productOld = wc_get_product( $product["id"] );
        if($productOld === false){
            return array(
                "status" => 400,
                "data" => "Product id [".$product["id"]."] Not Exist"
            );
        }

        switch ($product["type"]) {
            case 'variation':
                $newProductWoo = new WC_Product_Variation( $productOld->get_id( ));
                break;
            case 'variable':
                $newProductWoo = new WC_Product_Variable( $productOld->get_id( ));
                break;
            case 'simple':
                $newProductWoo = new WC_Product_Simple( $productOld->get_id( ));
                break;
            default:
                $newProductWoo = new WC_Product_Simple( $productOld->get_id( ));
                break;
        }   
    }

    if(!empty($product["sku"])){
        $newProductWoo->set_sku($product["sku"]);
    }
    $newProductWoo->save();
    if(!empty($product["name"])){
        $newProductWoo->set_name($product["name"]);
    }
    if(!empty($product["description"])){
        $newProductWoo->set_description($product["description"]);
    }
    if(!empty($product["short_description"])){
        $newProductWoo->set_short_description($product["short_description"]);
    }
    if(!empty($product["status"])){
        $newProductWoo->set_status($product["status"]);
    }
    if(!empty($product["visibility"])){
        $newProductWoo->set_catalog_visibility($product["visibility"]);
    }
    if(!empty($product["sale_price"])){
        $newProductWoo->set_sale_price($product["sale_price"]);
    }
    if(!empty($product["regular_price"])){
        $newProductWoo->set_regular_price($product["regular_price"]);
    }
    if(!empty($product["downloadable"])){
        $newProductWoo->set_downloadable($product["downloadable"]);
    }
    if(!empty($product["virtual"])){
        $newProductWoo->set_virtual($product["virtual"]);
    }
    if(!empty($product["stock"])){
        $newProductWoo->set_manage_stock($product["stock"]);
    }
    if(!empty($product["stock_quantity"])){
        $newProductWoo->set_stock_quantity($product["stock_quantity"]);
    }
    if(!empty($product["stock_quantity"])){
        $newProductWoo->set_stock_quantity($product["stock_quantity"]);
    }
    if(!empty($product["stock_quantity"])){
        $newProductWoo->set_stock_quantity($product["stock_quantity"]);
    }
    if(!empty($product["stock_quantity"])){
        $newProductWoo->set_stock_quantity($product["stock_quantity"]);
    }

    $categories = CWWYA_createCategories($product["categories"]);
    $newProductWoo->set_category_ids($categories);

    $tags = CWWYA_createTags($product["tags"]);
    $newProductWoo->set_tag_ids($tags);

    if(!empty($product["weight"])){
        $newProductWoo->set_weight($product["weight"]);
    }
    if(!empty($product["width"])){
        $newProductWoo->set_width($product["width"]);
    }
    if(!empty($product["length"])){
        $newProductWoo->set_length($product["length"]);
    }
    if(!empty($product["height"])){
        $newProductWoo->set_height($product["height"]);
    }

    $newProductWoo->save();

    $product["product_id"] = $newProductWoo->get_id();
    $product["product_url"] = get_permalink( $newProductWoo->get_id() );
    
    if(!empty($product["featured"])){
        $img = CWWYA_upload_image($product["featured"]);
        if($img){
            $newProductWoo->set_image_id( $img["attachment_id"] );
        }
        update_post_meta($product["product_id"],"img",wp_json_encode($img));
    }

    if(!empty($product["gallery_image"])){
        $imgGalery =  $product["gallery_image"];
        $imgGaleryUse =  [];
        for ($i=0; $i < count($imgGalery); $i++) { 
            $img = CWWYA_upload_image($imgGalery[$i]);  
            $imgGaleryUse[] = $img["attachment_id"];
        }
        update_post_meta($product["product_id"],"gallery_image".$i,wp_json_encode($imgGaleryUse));
        $newProductWoo->set_gallery_image_ids( $imgGaleryUse ); 
    }

    $newProductWoo->save();
    
    if(!empty($product["custom_meta"])){
        foreach ($product["custom_meta"] as $key => $value) {
            update_post_meta($product["product_id"],$key,$value);
        }
    }

    if(!empty($product["attributes"])){
        $attributes = [];
        foreach( $product["attributes"] as $key => $attribute_array ) {
            if( isset($attribute_array['name']) && isset($attribute_array['options']) ){
                $attribute = new WC_Product_Attribute();
                $attribute->set_name($attribute_array['name']);
                $attribute->set_options($attribute_array['options']);
                $attribute->set_visible(true);
                $attribute->set_variation(true);
                $attributes[$attribute_array['name']] =  $attribute;
            }
        }
        $newProductWoo->set_attributes( $attributes );
    }

    if(!empty($product["variations"])){
        $variations = get_post_meta($newProductWoo->get_id( ),"variations_aveonline",true);
        $variations = json_decode($variations,true);
        $idsVariations = [];
        foreach ($variations as $key => $variation) {
            if($variation["status"] == 200 ){
                $idsVariations[$variation["product"]["id"]] = $variation["product"]["product_id"];
            }
        }
        foreach ($product["variations"] as $key => $variation) {
            $variation_id = $idsVariations[$variation["id"]];
            if($variation_id){
                $productVariation = new WC_Product_Variation( $variation_id );
                $product["variations"][$key] = CWWYA_updateProduct($variation,$productVariation);

                $variation_id = $productVariation->get_id();

                foreach( $variation["attributes"] as $key => $attribute_array ) {
                    if( isset($attribute_array['name']) && isset($attribute_array['option']) ){
                        wp_set_object_terms( $variation_id, $attribute_array["option"] , $attribute_array["name"] );
                        update_post_meta($variation_id,"attribute_".$attribute_array["name"],$attribute_array["option"]);
                    }
                }
            }else{
                $result = CWWYA_createProduct_Simple($variation,"variation");
                if($result["status"] != 200){
                    $product["variations"][$key] = $result;
                }else{
                    $variation_id = $result["product"]["product_id"];
                    $variation_product = wc_get_product( $variation_id );
                    foreach( $variation["attributes"] as $key => $attribute_array ) {
                        wp_set_object_terms( $variation_id, $attribute_array["option"] , $attribute_array["name"] );
                        update_post_meta($variation_id,"attribute_".$attribute_array["name"],$attribute_array["option"]);
                    }
                    $variation_product->set_parent_id($newProductWoo->get_id());
                    $variation_product->save();
                    $product["variations"][$key] = $result;
                }
            }
        }
    }

    $newProductWoo->save();

    return $product;
}