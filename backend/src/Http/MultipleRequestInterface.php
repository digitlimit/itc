<?php

namespace IT\Insurance\Http;

interface MultipleRequestInterface
{
    /**
     * Perform multiple request concurrently
     * 
     * @return array
     */
    public function getMultiple(string $url, array $data=[]) : array;
}