<?php

namespace ITC\Insurance\Controllers;

use ITC\Insurance\Http\CurlHttp;

class InsuranceController extends BaseController
{
    public function __construct()
    {
        
    }

    /**
     * Return a JSON response
     * 
     * @return array
     */
    public function response() : array 
    {
        $curlHttp = new CurlHttp('https://itccompliance.co.uk/recruitment-webservice/api');
        $curlHttp->getMultiple();
        
        return [];
    }
}