<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_deleteUser($user_id)
{
    $user = get_user_by("id",$user_id);
    if(!$user){
        throw new Exception("User id [".$user_id."] Not Exist");
    }
    $user = json_decode(wp_json_encode($user),true);
    if(!in_array("customer",$user["roles"])){
        throw new Exception("User id [".$user_id."] Not Exist");
    }
    
	require_once( ABSPATH . 'wp-admin/includes/user.php' );
    wp_delete_user($user_id);
    return array(
        "status" => 200,
        "user_delete" => $user_id
    );
}
function CWWYA_deleteUsers($data)
{
    $user_id = $data["user_id"];
    if( empty( $user_id) ) {
        throw new Exception('user id Required');
    }
    $result = CWWYA_deleteUser($user_id);
    return $result;
}