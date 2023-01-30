<?php

namespace ITC\Insurance\Controllers;

use ITC\Insurance\Services\Product;

class InsuranceController extends BaseController
{
    /**
     * Return a JSON response
     * 
     * @return array
     */
    public function response() : array 
    {
        // get product list
        $products = Product::list();

        // get product details
        $details  = Product::info($products);

        // transform and return sanitized details
        return Product::transformInfo($details);
    }
}