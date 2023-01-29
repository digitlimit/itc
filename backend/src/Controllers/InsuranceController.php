<?php

namespace ITC\Insurance\Controllers;

class InsuranceController extends BaseController
{
    /**
     * Return a JSON response
     * 
     * @return array
     */
    public function response() : array 
    {
        return $products = $this->getProducts();

        // get details
        // $details = $this->getProductDetails($products);


        return [];
    }

    protected function getProducts() : array
    {
        $curl = new CurlHttp('https://itccompliance.co.uk/recruitment-webservice/api');
        $curl->setCallback(fn($data) => isset($data['products'])); 

        // get products
        $list = $curl->get('list');

        return $list['products'];
    }

    protected function getProductDetails(array $products)
    {
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
    }
}