<?php

namespace ITC\Insurance\Http;

abstract class BaseHttp
{
    /**
     * The base url 
     * 
     * @var
     */
    protected string $baseUrl = '';

    /**
     * The full url or path
     * 
     * @var string
     */
    protected string $url;

    /**
     * Request method 
     * 
     * @var string
     */
    protected string $method;

    /**
     * Request parameters
     * 
     * @var array
     */
    protected array $params = [];

    /**
     * Status Code
     * 
     * @var
     */
    protected int $statusCode;

    /**
     * Status codes that can be retryed
     * 
     * @var
     */
    protected array  $retryStatusCodes = [
        408, // - Request Timeout
        503, // - Unavailable
        504, // - Gateway Timeout
        500, // - Internal Server Error
        501, // - Not Implemented
        502, // - Bad Gateway
        503, // - Service Unavailable
        504, // - Gateway Timeout
        506, // - Variant Also Negotiates
        507, // - Insufficient Storage
        508, // - Loop Detected
        509, // - Bandwidth Limit Exceeded
        510, // - Not Extended
        520, // - Web server is returning an unknown error
        521, // - Web server is down
        522, // - Connection timed out
        523, // - Origin is unreachable
        524, // - A Timeout Occurred
        525, // - SSL handshake failed
        529, // - The service is overloaded
        530, // - Site Frozen
        598, // - Network read timeout error
    ];

    /**
     * Number of retries so far
     * 
     * @var
     */
    protected int $retries = 0;

    /**
     * Maximum number of retries
     * 
     * @var
     */
    protected int $maxRetry = -1;

    /**
     * Callback for validating returned data
     * A retry will be triggered if callback returns false
     * 
     * @var callable
     */
    protected $callback;

    /**
     * Set the base url of the request
     * 
     * @return self
     */
    public function setBaseUrl(string $baseUrl) : self 
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * Set the url of the request
     * 
     * @return self
     */
    public function setUrl(string $url) : self 
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set the request method
     * 
     * @return self
     */
    public function setMethod(string $method) : self 
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Set request parameters
     * 
     * @return self
     */
    public function setParams(array $params) : self 
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Set the status code
     * 
     * @return self
     */
    public function setStatusCode(int $statusCode) : self 
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Set the rety status codes
     * 
     * @return self
     */
    public function setRetryStatusCodes(array $retryStatusCodes) : self
    {
        $this->retryStatusCodes = $retryStatusCodes;
        return $this;
    }

    /**
     * Set max retry
     * 
     * @return self
     */
    public function setMaxRetry(int $maxRetry) : self
    {
        $this->maxRetry = $maxRetry;
        return $this;
    }

    /**
     * Set retries
     * 
     * @return self
     */
    public function setRetries(int $retries) : self
    {
        $this->retries = $retries;
        return $this;
    }

    /**
     * Get the base url of the request
     * 
     * @return string
     */
    public function getBaseUrl() : string
    {
        return $this->baseUrl;
    }

    /**
     * Get the url of the request
     * 
     * @return string
     */
    public function getUrl() : string
    { 
        if($this->baseUrl) {
            return rtrim($this->baseUrl, '/') . '/' . ltrim($this->url, '/');
        }

        return $this->url;
    }

    /**
     * Get request method
     * 
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Get request params
     * 
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * Get the status code
     * 
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    /**
     * Get the retry status codes
     * 
     * @return array
     */
    public function getRetryStatusCodes() : array
    {
        return $this->retryStatusCodes;
    }

    /**
     * Get get max retry
     * 
     * @return int
     */
    public function getMaxRetry() : int
    {
        return $this->maxRetry;
    }

    /**
     * Get get max retry
     * 
     * @return int
     */
    public function getRetries() : int
    {
        return $this->retries;
    }

    /**
     * Add a callback for data validation 
     * 
     * @return self
     */
    public function setCallback(callable $callback) : self
    {
        $this->callback = $callback;
        return $this;
    }
}