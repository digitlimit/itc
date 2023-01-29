<?php

namespace ITC\Insurance\Controllers;

class BaseController implements ControllerInterface
{
    /**
     * Return an array response
     * 
     * @return array
     */
    public function response() : array 
    {
        
        return [];
    }

    /**
     * Return a JSON response
     * 
     * @return array
     */
    public function responseJson() : string 
    {
        return json_encode($this->response());
    }

    /**
     * Print a json sting output
     * 
     * @return void
     */
    public function outputJson() : void 
    {
        header("Content-Type: application/json; charset=UTF-8");
        echo $this->responseJson();
    }

    /**
     * To string
     */
    public function __toString()
    {
        return $this->responseJson();
    }
}