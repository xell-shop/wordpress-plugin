<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_saveConfigAndApis($data)
{
    if($data["save"] == "Save"){
        $defaults = CWWYA_getConfigDefault();
        $configDefault = $defaults["configDefault"];

        $data["config"] = CWWYA_joinArrayObject($configDefault,$data["config"]);

        if($data["config"]){
            foreach ($data["config"] as $key => $value) {
                if($value == "on"){
                    $data["config"][$key] = true;
                }
                if($value == "off"){
                    $data["config"][$key] = false;
                }
            }
            CWWYA_set_option("config",$data["config"]);
        }
        if($data["api"]){
            for ($i=0; $i < count($data["api"]); $i++) { 
                foreach ($data["api"][$i]["permission"] as $key => $value) {
                    if($value == "on"){
                        $data["api"][$i]["permission"][$key] = true;
                    }
                    if($value == "off"){
                        $data["api"][$i]["permission"][$key] = false;
                    }
                }
            }
            $newsApi = $data["api"];
            $olds = CWWYA_get_option("apis");

            $newsApiD = $newsApi;
            $oldsD = $olds;
            for ($i=0; $i < count($olds); $i++) { 
                for ($j=0; $j < count($newsApi); $j++) { 
                    if($olds[$i]["name"] == $newsApi[$j]["name"]){
                        unset($oldsD[$i]);
                        unset($newsApiD[$j]);
                    }
                }
            }
            if(count($oldsD)>0){
                for ($i=0; $i < count($oldsD); $i++) { 
                    CWWYA_alertDisconnect($oldsD[$i]);
                }
            }

            if(count($newsApiD)>0){
                for ($i=0; $i < count($newsApiD); $i++) { 
                    CWWYA_alertConnect($newsApiD[$i]);
                }
            }
            CWWYA_set_option("apis",$data["api"]);
        }
    }
}
