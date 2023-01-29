<?php

namespace ITC\Insurance\Http;

use CurlMultiHandle;
use Exception;

class MultiCurlHttp extends BaseHttp implements MultipleRequestInterface
{
    /**
     * Callback for data validation
     * Return true for valid data or false for invalid data
     * 
     * @var callable 
     */
    protected array $requests = [];

    /**
     * Curl multi
     * 
     * @var CurlMultiHandle
     */
    protected CurlMultiHandle $handle;

    /**
     * Array of 
     * 
     * @var CurlMultiHandle
     */
    protected $handles = [];

    /**
     * Callback for validating returned data
     * A retry will be triggered if callback returns false
     * 
     * @var callable
     */
    protected $callback;

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
     * Add an instance on MultipleRequest which will be used for concurrent request
     * 
     * @return self
     */
    public function addRequest(MultipleRequest $request) : self
    {
        $this->requests[] = $request;
        return $this;
    }

    /**
     * Perform multiple request concurrently
     * 
     * @return array
     */
    public function get(array $multipleRequests=[]) : array
    {
        $this->handle = curl_multi_init();

        foreach($multipleRequests as $request) {
            $this->addRequest($request);
        }

        return $this->execute();
    }

    /**
     * Retry failed request or failed data validation
     * 
     * @param int   $code HTTP status code
     * @param array $data Response from the request
     * 
     * @return array
     */
    protected function retry(int $code, array $data) : array
    {
        $this->retries++;

        $maxRetry = $this->getMaxRetry();
        $codes    = $this->getRetryStatusCodes();

        // var_dump($this->retries, $maxRetry);die;
        if(in_array($code, $codes)) {
            // echo "Retrying ....  ($this->retries)\n";
            sleep(1);
            $this->handle = curl_multi_init();
            return $this->execute();
        }

        return $data;
    }

    /**
     * Execute multiple curl requests
     * 
     * @return array
     */
    protected function execute() : array
    {
        foreach($this->requests as $request)
        {
            // create both cURL resources
            $curlHandle = curl_init(); 
            $url = $request->getUrl();

            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($this->handle, $curlHandle);

            $this->handles[$url] = $curlHandle;
        }

        //execute the handles
        $active = null;
        do {
            $mrc = curl_multi_exec($this->handle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    
        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($this->handle) != -1) {
                do {
                    $mrc = curl_multi_exec($this->handle, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
       
        // get contents
        $data = [];
        foreach($this->handles as $url=>$ch) 
        {
            $data[] = curl_multi_getcontent($ch); 
            curl_multi_remove_handle($this->handle, $ch); 
        }
 
        // close curl multi
        curl_multi_close($this->handle);

        return $data;
    }
}