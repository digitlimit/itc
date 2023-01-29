<?php

namespace ITC\Insurance\Services;

use Exception;
use ITC\Insurance\Http\CurlHttp;
use ITC\Insurance\Http\MultiCurlHttp;
use ITC\Insurance\Http\MultipleRequest;

class Product
{
    /**
     * Get a list of products
     * 
     * @return array
     */
    public static function list() : array
    {
        try {
            $curl = new CurlHttp('https://itccompliance.co.uk/recruitment-webservice/api');
            $curl->setCallback(fn($data) => isset($data['products'])); 

            // get products
            $list = $curl->get('list');
            return $list['products'];
        } catch(Exception $e) {
            return [
                'error' => $e->getMessage(),
                'code'  => 501
            ];
        }
    }

    /**
     * Get products detail
     * 
     * @return array
     */
    public static function info(array $products)
    {
        try {
            // set up multi curl request
            $requests = [];
            foreach($products as $id => $product) 
            {
                $request = new MultipleRequest();
                $request
                    ->setUrl("https://itccompliance.co.uk/recruitment-webservice/api/info?id={$id}");

                $requests[$id] = $request;
            }

            $curl = new MultiCurlHttp();

            // data validation callback
            $curl->setCallback(function($data){

            }); 

            return $curl->get($requests);

        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
                'code'  => 501
            ];
        }
    }

    /**
     * Sanitize and transform info
     * 
     * @param array $details An array of product info
     * 
     * @return array
     */
    public function transformInfo(array $details)
    {
        $newDetails = [];

        return $newDetails;
    }
}