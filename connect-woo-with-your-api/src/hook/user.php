<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_sendHookUser($type,$user_id)
{
    $user = CWWYA_getUser($user_id);
    CWWYA_send_all_apis($type,$user);
}

function CWWYA_onCreateUser($user_id)
{
    CWWYA_sendHookUser("user_create",$user_id);
}
add_action('user_register', 'CWWYA_onCreateUser', 10, 1);

function CWWYA_onUpdateUser($user_id)
{
    CWWYA_sendHookUser("user_update",$user_id);
}
add_action('profile_update', 'CWWYA_onUpdateUser', 10, 2);

function CWWYA_onDeleteUser($user_id)
{
    CWWYA_sendHookUser("user_delete",$user_id);
}
add_action('delete_user', 'CWWYA_onDeleteUser', 10, 1);