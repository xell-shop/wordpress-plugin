<?php


if ( ! defined( 'ABSPATH' ) ) exit;
class CWWYA_api
{
    private $NAME = "";
    private $URL = "";
    private $TOKEN = "";
    

    public function __construct($settings = array())
    {
        $this->NAME = $settings["name"];
        $this->URL = $settings["url"];
        $this->TOKEN = $settings["token"];
    }

    private function request($json)
    {

        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => $this->URL,
        //     CURLOPT_SSL_VERIFYPEER => false,
        //     CURLOPT_SSL_VERIFYHOST => false,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_VERBOSE => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS => wp_json_encode($json),
        //     CURLOPT_HTTPHEADER => array(
        //         "Content-Type: application/json",
        //         'Authorization: '.$this->TOKEN,
        //     ),
        // ));
        // $response = curl_exec($curl);
        // $error = curl_error($curl);
        // curl_close($curl);
        // if($error){
        //     return $error;
        // }
        // $response = json_decode($response,true);

        $response = wp_remote_post($this->URL, array(
            'body'    => $json,
            'headers' => array(
                "Content-Type" => "application/json",
                'Authorization' => $this->TOKEN,
            ),        
        ));

        if ( is_wp_error( $response ) ) {
            throw $response;
        }
        return $response;
    }
    public function send($type,$data)
    {
        $dataSend = array(
            "type" => $type,
            "data" => $data,
            "url"  => CWWYA_URL
        );
        try {
            $result = $this->request($dataSend);
            CWWYA_LOG_add(array(
                "api" => $this->NAME,
                "url" => $this->URL,
                "CWWYA_URL" => CWWYA_URL,
                "send" => "ok",
                "type" => $type,
                "data" => $data,
                "result" => $result,
            ));
            return array(
                "type" => "ok",
                "data" => $result
            );
        } catch (\Throwable $th) {
            CWWYA_LOG_add(array(
                "api" => $this->NAME,
                "type" => "error",
                "error" => json_decode(wp_json_encode($th),true),
                "dataSend" => $dataSend
            ));
            return array(
                "type" => "error",
                "error" => $th
            );
        }
    }
}
