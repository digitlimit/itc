<?php

namespace ITC\Insurance\Http;

interface MultipleRequestInterface
{
    /**
     * Perform multiple request concurrently
     * 
     * @return array
     */
    public function get(array $multipleRequests=[]) : array;

    /**
     * Add an instance on MultipleRequest
     * 
     * @return self
     */
    public function addRequest(MultipleRequest $request) : self;
}