<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_getConfigDefault()
{
    $configDefault = array(
		"active" => false,
	);
	$permissionsDefault = array(
		"product_ready" => false,
		"product_create" => false,
		"product_update" => false,
		"product_delete" => false,
		"order_ready" => false,
		"order_create" => false,
		"order_update" => false,
		"order_delete" => false,
		"user_ready" => false,
		"user_create" => false,
		"user_update" => false,
		"user_delete" => false,
	);
	$apiDefault = array(
		"active" => false,
		"name" => "",
		"url" => "",
		"token" => "",
		"permissions" => $permissionsDefault
	);
    return array(
        "configDefault" => $configDefault,
        "permissionsDefault" => $permissionsDefault,
        "apiDefault" => $apiDefault,
    );
}
