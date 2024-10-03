<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_validate_postUser($user)
{
    $username = $user["username"];
    if( empty( $username) ) {
        throw new Exception('username Required');
    }
    $password = $user["password"];
    if( empty( $password) ) {
        throw new Exception('password Required');
    }
    $email = $user["email"];
    if( empty( $email) ) {
        throw new Exception('email Required');
    }
    
}
function CWWYA_validate_postUsers($users)
{
    if( empty($users) ) {
        throw new Exception('Users Required');
    }
    if( !is_array($users) ) {
        throw new Exception('Users is not array');
    }
    if( count($users) == 0 ) {
        throw new Exception('Users is not array');
    }
}

function CWWYA_postUser($user)
{
    try {
        CWWYA_validate_postUser($user);
        $user_id = wp_create_user($user["username"],$user["password"],$user["email"]);

        if(is_wp_error($user_id)){
            $user_id = json_decode(wp_json_encode($user_id),true);
            throw new Exception(wp_json_encode($user_id["errors"]));
        }

        $userSetMeta = $user;
        $userSetMeta["id"] = $user_id;

        $userCreate = new WP_User($user_id);
        $userCreate->remove_role( 'subscriber' );
        $userCreate->add_role( 'customer' );
        CWWYA_setMetaUser($userSetMeta);
        return array(
            "status" => 200,
            "user" => CWWYA_getUser($user_id)
        );
    } catch (Exception $e) {
        return array(
            "status" => 400,
            "data" => $e->getMessage(),
        );
    }
}

function CWWYA_postUsers($data)
{
    $newUsers = $data["users"];
    CWWYA_validate_postUsers($newUsers);
    $users = [];
    for ($i=0; $i < count($newUsers); $i++) { 
        $users[] = CWWYA_postUser($newUsers[$i]);
    }

    return $users;
}