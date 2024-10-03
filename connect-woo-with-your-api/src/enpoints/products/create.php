<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_validate_postProduct($product){

}
function CWWYA_validate_postProducts($products)
{
    if( empty($products) ) {
        throw new Exception('Products Required');
    }
    if( !is_array($products) ) {
        throw new Exception('Products is not array');
    }
    if( count($products) == 0 ) {
        throw new Exception('Products is not array');
    }
}

/**
 * CWWYA_createProduct_Simple
 * @param newProduct(array(
 *      name string,
 *      sku string,
 *      description string
 *      short_description string
 * 
 *      status string [publish,pending,draft]
 *      visibility string [visible]
 * 
 *      price float 
 *      regular_price float
 * 
 *      downloadable bool
 *      virtual bool
 * 
 *      categories array string 
 *      tags array string 
 * 
 *      stock_quantity int
 * 
 *      featured string
 *      gallery_image array string
 * 
 *      weight float
 *      width float
 *      length float
 *      height float
 * ))
 */

function CWWYA_createProduct_Simple($newProduct = [],$type="simple")
{
    $newProduct = CWWYA_validateFieldDefault(array(
        array(
            "key"=>"downloadable",
            "value"=>false,
        ),
        array(
            "key"=>"virtual",
            "value"=>false,
        ),
        array(
            "key"=>"custom_meta",
            "value"=>[],
        ),
    ),$newProduct);
    $result = CWWYA_validateFieldRequired(array(
        "id",
        "name",
        "sku",
        // "description",
        // "short_description",
        "status",
        "visibility",
        // "sale_price",
        // "regular_price",
        // "stock",
        // "stock_quantity",
        // "featured",
        // "gallery_image",
        // "categories",
        // "tags",
        "weight",
        "width",
        "length",
        "height",
    ),$newProduct);
    if($result["status"] != 200){
        return $result;
    }
    $args = array(
        'post_type' => 'product',
        'meta_key' => 'id_CWWYA',
        'meta_value' => $newProduct["id"]
    );
    $productsOld = wc_get_products($args);
    if(count($productsOld) > 0){
        return array(
            "status" => 400,
            "data" => "Product id [".$newProduct["id"]."] Exist"
        );
    }
    try {
        switch ($type) {
            case 'variation':
                $product = new WC_Product_Variation();
                break;
            case 'variable':
                $product = new WC_Product_Variable();
                break;
            case 'simple':
                $product = new WC_Product_Simple();
                break;
            default:
                $product = new WC_Product_Simple();
                break;
        }
        
        $product->set_name( $newProduct["name"] );
        $product->set_sku( $newProduct["sku"] );
        
        $product->set_description( $newProduct["description"] );
        $product->set_short_description( $newProduct["short_description"] );

        $product->set_status( $newProduct["status"]); 
        $product->set_catalog_visibility( $newProduct["visibility"] );

        $product->set_sale_price( $newProduct["sale_price"] );
        $product->set_regular_price( $newProduct["regular_price"] );

        $product->set_downloadable( $newProduct["downloadable"] );
        $product->set_virtual( $newProduct["virtual"] );   

        $product->set_manage_stock( $newProduct["stock"] );
        $product->set_stock_quantity( $newProduct["stock_quantity"] );

        if($newProduct["categories"]){
            $categories = CWWYA_createCategories($newProduct["categories"]);
            $product->set_category_ids($categories);
        }

        if($newProduct["tags"]){
            $tags = CWWYA_createTags($newProduct["tags"]);
            $product->set_tag_ids($tags);
        }

        $product->set_weight( $newProduct["weight"] );
        $product->set_width( $newProduct["width"] );  
        $product->set_length( $newProduct["length"] );
        $product->set_height( $newProduct["height"] );   
        
        $product->save();

        $newProduct["product_id"] = $product->get_id();
        $newProduct["product_url"] = get_permalink( $product->get_id() );
        update_post_meta($newProduct["product_id"],"id_CWWYA",$newProduct["id"]);
        
        if($newProduct["featured"]!=null && !empty($newProduct["featured"])){
            $img = CWWYA_upload_image($newProduct["featured"]);
            if($img){
                $product->set_image_id( $img["attachment_id"] );
            }
            update_post_meta($newProduct["product_id"],"img",wp_json_encode($img));
        }

        if($newProduct["gallery_image"]!=null && !empty($newProduct["gallery_image"])){
            $imgGalery =  $newProduct["gallery_image"];
            $imgGaleryUse =  [];
            for ($i=0; $i < count($imgGalery); $i++) { 
                $img = CWWYA_upload_image($imgGalery[$i]);  
                $imgGaleryUse[] = $img["attachment_id"];
            }
            update_post_meta($newProduct["product_id"],"gallery_image".$i,wp_json_encode($imgGaleryUse));
            $product->set_gallery_image_ids( $imgGaleryUse ); 
        }

        $product->save();

        foreach ($newProduct["custom_meta"] as $key => $value) {
            update_post_meta($newProduct["product_id"],$key,$value);
        }

        return array(
            "status" => 200,
            "product" => $newProduct 
        );
    } catch (Exception $e) {
        return (array(
            "status" => 400,
            "data" => $e->getMessage(),
            "product"=>$newProduct,
        ));
    }

}
/**
 * CWWYA_createProduct_Variable
 * @param newProduct(array(
 *      name string,
 *      sku string,
 *      description string
 *      short_description string
 * 
 *      status string [publish,pending,draft]
 *      visibility string [visible]
 * 
 *      price float 
 *      regular_price float
 * 
 *      downloadable bool
 *      virtual bool
 * 
 *      categories array string 
 *      tags array string 
 * 
 *      stock_quantity int
 * 
 *      featured string
 *      gallery_image array string
 * 
 *      weight float
 *      width float
 *      length float
 *      height float
 * 
 *      attributes array string
 *      variations array (
 *          newProduct all WC_Product_Simple
 *      )
 * ))
 */

function CWWYA_createProduct_Variable($newProduct = [])
{
    $result = CWWYA_validateFieldRequired(array(
        "attributes",
        "variations",
    ),$newProduct);
    if($result["status"] != 200){
        return $result;
    }

    $result = CWWYA_createProduct_Simple($newProduct,"variable");
    if($result["status"]!=200){
        return $result;
    }
    $parent_id = $result["product"]["product_id"];

    $parent = wc_get_product( $parent_id );
    $attributes = [];
    foreach( $newProduct["attributes"] as $key => $attribute_array ) {
        if( isset($attribute_array['name']) && isset($attribute_array['options']) ){
            $attribute = new WC_Product_Attribute();
            $attribute->set_name($attribute_array['name']);
            $attribute->set_options($attribute_array['options']);
            $attribute->set_visible(true);
            $attribute->set_variation(true);
            $attributes[$attribute_array['name']] =  $attribute;
        }
    }
    $parent->set_attributes( $attributes );
    $parent->save();

    $variations = $newProduct["variations"];
    $result["variations"] = [];

    for ($i=0; $i < count($variations); $i++) { 
        $newVariation = $variations[$i];
        $newVariationReturn  = '' ;
        try {
            $variation = CWWYA_createProduct_Simple($newVariation,"variation");
            if($variation["status"] != 200){
                $newVariationReturn = $variation;
            }else{
                $variation_id = $variation["product"]["product_id"];
                $variation_product = wc_get_product( $variation_id );
                foreach( $newVariation["attributes"] as $key => $attribute_array ) {
                    wp_set_object_terms( $variation_id, $attribute_array["option"] , $attribute_array["name"] );
                    update_post_meta($variation_id,"attribute_".$attribute_array["name"],$attribute_array["option"]);
                }
                $variation_product->set_parent_id($parent_id);
                $variation_product->save();
                $newVariationReturn = $variation;
            }
        } catch (Exception $e) {
            $newVariationReturn =  (array(
                "status" => 400,
                "data" => $e->getMessage(),
                "variation"=>$newVariation,
            ));
        }
        $result["variations"][] = $newVariationReturn;
    }
    update_post_meta($parent_id,"variations_CWWYA",wp_json_encode($result["variations"]));
    return $result;
}
function CWWYA_postProduct($newProduct){
    switch ($newProduct["type"]) {
        case 'simple':
            $newProductResult = CWWYA_createProduct_Simple($newProduct);
            break;
        case 'variable':
            $newProductResult = CWWYA_createProduct_Variable($newProduct);
            break;
        default:
            $newProductResult = array(
                "status" => 400,
                "data" => "type invalid"
            );
            break;
    }
    return $newProductResult ;
}

function CWWYA_postProducts($data)
{
    $newProducts = $data["products"];
    CWWYA_validate_postProducts($newProducts);

    $result = [];
    for ($i=0; $i < count($newProducts); $i++) { 
        $newProduct = $newProducts[$i];
        
        $newProductResult = CWWYA_postProduct($newProduct);

        $result[$newProductResult["status"]==200?"ok":"error"][] = $newProductResult;
    }
    return $result;
}