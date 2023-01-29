<?php

namespace ITC\Insurance\Http;

interface GetRequestInterface
{
    /**
     * Get method
     * 
     * @var string
     */
    const GET = 'GET';

    /**
     * Perform a GET request
     * 
     * @return array
     */
    public function get(string $url, array $data=[]) : array;
}