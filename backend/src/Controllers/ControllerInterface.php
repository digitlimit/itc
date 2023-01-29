<?php

namespace ITC\Insurance\Controllers;

interface ControllerInterface
{
    /**
     * Return a JSON response
     * 
     * @return array
     */
    public function response() : array;
}