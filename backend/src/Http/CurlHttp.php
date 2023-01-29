<?php

namespace ITC\Insurance\Http;

use CurlHandle;
use Exception;

class CurlHttp extends BaseHttp implements GetRequestInterface
{
    /**
     * Curl handle
     * 
     * @var CurlHandle
     */
    protected CurlHandle $handle;

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
        $this->handle = curl_init();

        $this->setRetries(0);
        $this->setMethod(self::GET);
        $this->setUrl($url);
        $this->setParams($params);

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
    protected function retry(int $code, array $data)
    {
        $this->retries++;

        $maxRetry = $this->getMaxRetry();
        $codes    = $this->getRetryStatusCodes();

        // var_dump($this->retries, $maxRetry);die;
        if(in_array($code, $codes)) {
            // echo "Retrying ....  ($this->retries)\n";
            sleep(1);
            $this->handle = curl_init();
            return $this->execute();
        }

        return $data;
    }

    /**
     * Execute curl request
     * 
     * @return array
     */
    protected function execute() : array
    {
        curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, $this->getMethod());
        curl_setopt($this->handle, CURLOPT_URL, $this->getUrl());
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handle, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
       
        if(!empty($this->getParams())){
            curl_setopt(
                $this->handle, 
                CURLOPT_POSTFIELDS, 
                http_build_query($this->getParams())
            );
        }

        try{
            $response = curl_exec($this->handle);
            $error    = curl_error($this->handle);
            $code     = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
            $data     = json_decode($response, true) ?? [];

            if($error){
                throw new Exception('Unknown error');
            } 

            if($this->callback && !call_user_func($this->callback, $data)) {
                throw new Exception('Callback validation failed');
            }
           
        } catch(Exception $e) {
            $code = 500;
            return $this->retry($code, $data);
        }

        curl_close($this->handle);
        return $data;
    }
}