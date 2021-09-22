<?php
namespace Savosik\OzonBundle\Ozon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class OzonDataProvider{
    private $client_id;
    private $api_key;
    private $method;
    private $url;
    private $request_arr;


    public function setClientId($client_id){
        $this->client_id = $client_id;
    }

    public function setApiKey($api_key){
        $this->api_key = $api_key;
    }

    public function getCategories($parent_category_id){
        $this->url = "https://api-seller.ozon.ru/v1/categories/tree?category_id=".$parent_category_id;
        $this->method = "GET";

        $res = $this->execute();
        return $res['result'];
    }


    public function getAttributesByCategoriesIds($categories_ids_array){
        $this->url = "https://api-seller.ozon.ru/v3/category/attribute";
        $this->method = "POST";
        $this->request_arr = [
            "attribute_type" => "ALL",
            "category_id" => $categories_ids_array,
            "language" => "DEFAULT"
        ];

        $res = $this->execute();
        return $res['result'];
    }


    public function getDictionaryElements($category_id, $attribute_id){
        $this->url = "https://api-seller.ozon.ru/v2/category/attribute/values";
        $this->method = "POST";
        $this->request_arr = [
            "attribute_id" => $attribute_id,
            "category_id" => $category_id,
            "language" => "DEFAULT",
            "last_value_id" => 0,
            "limit" => 1000
        ];

        $res = $this->execute();
        return ["has_next" => (bool) $res['has_next'], "elements" => $res['result']];
    }


    private function execute(){
        $client  = new Client();
        $response = $client->request(
            $this->method,
            $this->url,
            [
                'headers' => [
                    'Client-Id' => $this->client_id,
                    'Api-Key'   => $this->api_key
                ],
                'json' => $this->request_arr ?: null
            ]
        );

        $res = json_decode($response->getBody(), true);
        return $res;
    }


}
