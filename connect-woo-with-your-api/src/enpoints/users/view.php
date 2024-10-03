<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_getMetaUser($user){
    $user_id = $user["id"];
    if( empty( $user_id) ) {
        throw new Exception('user id Required');
    }
    $keys = [
        "first_name",
        "last_name",
        "url",
        "description",
        "billing_first_name",
        "billing_last_name",
        "billing_company",
        "billing_address_1",
        "billing_address_2",
        "billing_postcode",
        "billing_country",
        "billing_state",
        "billing_city",
        "billing_phone",
        "shipping_first_name",
        "shipping_last_name",
        "shipping_company",
        "shipping_address_1",
        "shipping_address_2",
        "shipping_postcode",
        "shipping_country",
        "shipping_state",
        "shipping_phone",
        "shipping_city",

    ];
    for ($i=0; $i < count($keys); $i++) { 
        $key = $keys[$i];
        $user[$key] = get_user_meta( $user_id, $key,true  );
    }
    return $user;
}
function CWWYA_getUser($user_id)
{
    $user = get_user_by("id",$user_id);
    if($user === false){
        throw new Exception('User_id invalid');
    }
    $user = json_decode(wp_json_encode($user),true);
    unset($user["data"]["user_pass"]);
    unset($user["data"]["user_activation_key"]);
    $user["data"]["id"] = $user_id;
    return CWWYA_getMetaUser($user["data"]);
}
function CWWYA_getUsers()
{
    $customer_query = new WP_User_Query(
        array(
        'fields' => 'ID',
        'role' => 'customer',         
        )
    );
    $all_ids = $customer_query->get_results();
    $users = [];
    foreach ( $all_ids as $id ) {
        try {
            $users[] = CWWYA_getUser($id);
        } catch (\Throwable $th) {
            $users[] = $tr;
        }
    }
    return $users;
}