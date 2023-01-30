<?php

namespace ITC\Insurance\Services;

class Base
{
   
    /**
     * Helper for sanitizing a string
     * 
     * @param array $details An array of product info
     * 
     * @return array
     */
    protected static function cleanString($string)
    { 
        $string = preg_replace("/[^[a-zA-Z0-9?><;,{}[\]\-_+=!@#$%\^&*|']*$]/", "", $string);
    
        $string = stripslashes($string);
        $string = htmlentities($string);
        $string = strip_tags($string);

        return $string;
    }
}