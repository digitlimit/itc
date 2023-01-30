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

    /**
     * Response contents
     * 
     * @var array
     */
    protected array $contents = [];

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
    protected function retry() : array
    {
        $this->retries++;

        $maxRetry = $this->getMaxRetry();
        $codes    = $this->getRetryStatusCodes();
        
        echo "Retrying ....  ($this->retries)\n";

        sleep(1);
        $this->handle = curl_multi_init();
        return $this->execute();
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
            $handle = curl_init(); 
            $url    = $request->getUrl();

            curl_setopt($handle, CURLOPT_URL, $url);
            curl_setopt($handle, CURLOPT_HEADER, 0);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($this->handle, $handle);

            $this->handles[$url] = $handle;
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
        $retryFlag = false;
        foreach($this->handles as $url => $handle) 
        {
            $response = curl_multi_getcontent($handle); 

            if($response) 
            {
                // convert response to array
                $content = json_decode($response, true);

                // run callback validation if given
                $valid   = $this->callback 
                    ? call_user_func($this->callback, $content) 
                    : true;

                // if valid remove and add to contents
                if($valid) {
                    $this->contents[$url] = $content;
                    curl_multi_remove_handle($this->handle, $handle); 
                }
            }
        }

        if($retryFlag) {
            return $this->retry();
        }
 
        // close curl multi
        curl_multi_close($this->handle);

        return $this->contents;
    }
}