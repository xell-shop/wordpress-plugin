<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_setMetaUser($user){
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
        "shipping_city",
        "shipping_phone",

    ];
    for ($i=0; $i < count($keys); $i++) { 
        $key = $keys[$i];
        if($user[$key]){
            update_user_meta( $user_id, $key , $user[$key] );
        }
    }
}
function CWWYA_validate_putUser($user)
{
    $id = $user["id"];
    if( empty( $id) ) {
        throw new Exception('user id Required');
    }
}
function CWWYA_putUser($user)
{
    try {
        CWWYA_validate_putUser($user);
        if($user["username"]){
            global $wpdb;
            $wpdb->update(
                $wpdb->users, 
                ['user_login' => $user["username"]], 
                ['ID' => $user["id"]]
            );
        }
        if($user["password"]){
            wp_set_password($user["password"],$user["id"]);
        }
        if($user["email"]){
            $user_data = wp_update_user( array( 'ID' => $user["id"],'user_email' => $user["email"] ) );
            if ( is_wp_error( $user_data ) ) {
                throw new Exception('user email Invalid');
            }
        }
        CWWYA_setMetaUser($user);
        return array(
            "status" => 200,
            "data" => "User update [".$user["id"]."]"
        );
    } catch (Exception $e) {
        return array(
            "status" => 400,
            "data" => $e->getMessage(),
        );
    }
}

function CWWYA_putUsers($data)
{
    $newUsers = $data["users"];
    CWWYA_validate_postUsers($newUsers);
    $users = [];
    for ($i=0; $i < count($newUsers); $i++) { 
        $users[] = CWWYA_putUser($newUsers[$i]);
    }

    return $users;
}