<?php

namespace ITC\Insurance\Http;

use CurlHandle;
use CurlMultiHandle;

class CurlHttp extends BaseHttp implements GetRequestInterface, MultipleRequestInterface
{
    /**
     * Callback for data validation
     * Return true for valid data or false for invalid data
     * 
     * @var callable 
     */
    protected array $multipleRequests = [];

    /**
     * Curl handles
     * 
     * @var array
     */
    protected array $curlHandles = [];

    /**
     * Curl multi
     * 
     * @var array
     */
    protected CurlMultiHandle $curlMultipleHandller;

    public function __construct(

        /**
         * The base url 
         * 
         * @var
         */
        protected string $baseUrl = '',

        /**
         * Maximum number of retries
         * 
         * @var
         */
        protected int $maxRetry  = 0
    ) {}

    /**
     * Perform a GET request
     * 
     * @return array
     */
    public function get(string $url, array $params=[]) : array
    {
        $this->setRetries(0);
        $this->setMethod(self::GET);
        $this->setUrl($url);
        $this->setParams($params);

        return $this->execute();
    }

    /**
     * Perform multiple request concurrently
     * 
     * @return array
     */
    public function getMultiple(array $multipleRequests=[]) : array
    {
        foreach($multipleRequests as $request) {
            $this->addMultipleRequest($request);
        }

        return $this->executMultiple();
    }

    /**
     * Add an instance on MultipleRequest which will be used for concurrent request
     * 
     * @return self
     */
    public function addMultipleRequest(MultipleRequest $request) : self
    {
        $this->multipleRequests[] = $request;
        return $this;
    }

    protected function execute() : array
    {

        return [];
    }

    /**
     * Execute multiple curl requests
     * 
     * @return array
     */
    protected function executMultiple() : array
    {
        
        $this->curlMultipleHandller = curl_multi_init(); // init the curl Multi
var_dump($this->curlMultipleHandller);
        // foreach($this->multipleRequests as $request)
        // {
        //     $this->curlHandles[]
        // }

        return [];
    }
}