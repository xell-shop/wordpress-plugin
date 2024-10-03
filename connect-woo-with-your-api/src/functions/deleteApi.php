<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_alertConnect($api)
{
    $apiS = new CWWYA_api($api);
    $apiS->send("connect",$api);
}
function CWWYA_alertDisconnect($api)
{
    $apiS = new CWWYA_api($api);
    $apiS->send("disconnect",$api);
}

function CWWYA_deleteApi($i)
{
	$apis = CWWYA_get_option("apis");
    CWWYA_alertDisconnect($apis[$i]);
    unset($apis[$i]); 
    $apis = array_values($apis);
    CWWYA_set_option("apis",$apis);
}

function CWWYA_active(){

    $defaults = CWWYA_getConfigDefault();
    $configDefault = $defaults["configDefault"];

    $data = CWWYA_joinArrayObject($configDefault,array(
        "active" => true
    ));
    CWWYA_set_option("config",$data);
}
function CWWYA_addApi($newApi)
{
	$apis = CWWYA_get_option("apis");
    $apis[] = $newApi;
    CWWYA_set_option("apis",$apis);
    CWWYA_alertConnect($newApi);
}

function CWWYA_setApiByName($name,$config)
{
	$apis = CWWYA_get_option("apis");
    for ($i=0; $i < count($apis); $i++) { 
        if($apis[$i]["name"] == $name){
            $apis[$i] = $config;
        }
    }
    CWWYA_set_option("apis",$apis);
}

function CWWYA_deleteApiByName($name)
{
	$apis = CWWYA_get_option("apis");
    for ($i=0; $i < count($apis); $i++) { 
        if($apis[$i]["name"] == $name){
            CWWYA_alertDisconnect($apis[$i]);
            unset($apis[$i]); 
        }
    }
    $apis = array_values($apis);
    CWWYA_set_option("apis",$apis);
}
