<?php

namespace ITC\Insurance\Http;

interface MultipleRequestInterface
{
    /**
     * Perform multiple request concurrently
     * 
     * @return array
     */
    public function getMultiple(array $multipleRequest=[]) : array;

    /**
     * Add an instance on MultipleRequest
     * 
     * @return self
     */
    public function addMultipleRequest(MultipleRequest $request) : self;
}