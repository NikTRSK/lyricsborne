<?php
namespace Genius;

class GeniusHttpRequest {
    private $access_token;
    private $genius_api_base_url = 'https://api.genius.com';
    private $curl;

    public function __construct($access_token){
        $this->access_token = $access_token;
        if(function_exists('curl_version')){
            $this->init();
        } else {
            Throw new \Genius\GeniusHttpRequestException( "No cURL installed." );
        }

    }

    public function init(){
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_SSLVERSION, 4); // Force SSLv3 to fix Unknown SSL Protocol error ADDED
        curl_setopt($this->curl, CURLOPT_SSL_CIPHER_LIST, 'SSLv3');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($this->curl, CURLOPT_USERAGENT, "GeniusPHPApi/0.0.1");
        curl_setopt($this->curl, CURLOPT_ENCODING, "application/json");
        curl_setopt($this->curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->access_token,
        ));
    }

    public function get($url, $params = array()){
//        print_r($this->curl." ". CURLOPT_HTTPGET." ". 1);
        curl_setopt($this->curl, CURLOPT_HTTPGET, 1);
        curl_setopt($this->curl, CURLOPT_URL, $this->genius_api_base_url . $url . '?' . http_build_query($params));
        return $this->exec();
    }

    public function exec(){
        $reponse = curl_exec($this->curl);
        if(!$reponse){
            throw new \Genius\GeniusHttpRequestException( "cURL error #" . curl_errno($this->curl) . ": " . curl_error($this->curl) );
        } else {
            return $reponse;
        }
    }

    public function __destruct(){
        curl_close($this->curl);
    }
}
class GeniusHttpRequestException extends \Exception {
}