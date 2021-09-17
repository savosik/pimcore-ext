<?php
namespace Savosik\OzonBundle\Ozon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OzonDataProvider{
    private $api_key;
    private $token;
    private $method;
    private $url;
    private $request_arr;

    public function setApiKey($api_key){
        $this->api_key = $api_key;
    }

    public function setToken($token){
        $this->token = $token;
    }

    public function getCategories($parent_category_id){
        $this->url = "https://api-seller.ozon.ru/v1/categories/tree?category_id=".$parent_category_id;
        $this->method = "GET";

        return $this->execute();
    }


    public function getAttributesByCategory($category_id){
        $this->url = "https://api-seller.ozon.ru/v3/category/attribute";
        $this->method = "POST";
        $this->request_arr = [
            "attribute_type" => "ALL",
            "category_id" => [$category_id],
            "language" => "DEFAULT"
        ];

        return $this->execute();
    }


    private function execute(){
        $client  = new Client();
        $response = $client->request(
            $this->method,
            $this->url,
            [
                'headers' => [
                    'Client-Id' => $this->api_key,
                    'Api-Key'   => $this->token
                ],
                'json' => $this->request_arr ?: null
            ]
        );

        $result = json_decode($response->getBody(), true);
        return $result['result'];
    }


}
