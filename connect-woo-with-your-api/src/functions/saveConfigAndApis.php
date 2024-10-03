<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_saveConfigAndApis()
{
    $SAVE = sanitize_text_field($_POST['save']);
    if($SAVE == "Save"){
        $defaults = CWWYA_getConfigDefault();
        $configDefault = $defaults["configDefault"];

        $CONFIG = CWWYA_sanitizeObj($_POST['config']);

        $CONFIG = CWWYA_joinArrayObject($configDefault,$CONFIG);

        if($CONFIG){
            foreach ($CONFIG as $key => $value) {
                if($value == "on"){
                    $CONFIG[$key] = true;
                }
                if($value == "off"){
                    $CONFIG[$key] = false;
                }
            }
            CWWYA_set_option("config",$CONFIG);
        }

        $API = CWWYA_sanitizeObj($_POST['api']);
        
        if($API){
            for ($i=0; $i < count($API); $i++) { 
                foreach ($API[$i]["permission"] as $key => $value) {
                    if($value == "on"){
                        $API[$i]["permission"][$key] = true;
                    }
                    if($value == "off"){
                        $API[$i]["permission"][$key] = false;
                    }
                }
            }
            $newsApi = $API;
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
            CWWYA_set_option("apis",$API);
        }
    }
}
