<?php

namespace ITC\Insurance\Services;

use Exception;
use ITC\Insurance\Http\CurlHttp;
use ITC\Insurance\Http\MultiCurlHttp;
use ITC\Insurance\Http\MultipleRequest;

class Product extends Base
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

            // data validation callback
            $curl->setCallback(fn($content) => !isset($content['error']));

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
            $curl->setCallback(fn($content) => !isset($content['error'])); 

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
    public static function transformInfo(array $details)
    { 
        $newDetails = [];

        foreach($details as $detail) 
        {
            $detail = array_values($detail)[0] ?? [];

            if(empty($detail)) {
                continue;
            }

            // clean up
            $suppliers = [];
            if(isset($detail['suppliers']) && is_array($detail['suppliers'])){
                foreach($detail['suppliers'] as $supplier) {
                    $suppliers[] = self::cleanString($supplier);
                }
            }

            // clean up
            $name        = self::cleanString($detail['name'] ?? '');
            $description = self::cleanString($detail['description'] ?? '');
            $type        = self::cleanString($detail['type'] ?? '');

            $newDetails[] = [
                'name'        => $name,
                'description' => $description,
                'type'        => ucwords($type),
                'suppliers'   => $suppliers,
            ];
        }

        return $newDetails;
    }
}