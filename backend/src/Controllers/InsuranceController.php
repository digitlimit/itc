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
}