<?php

namespace ITC\Insurance\Services;

class Base
{
   
    /**
     * Helper for sanitizing a string
     * 
     * @param string $string String that will be clean
     * 
     * @return string
     */
    protected static function cleanString($string)
    { 
        $string = preg_replace("/[^[a-zA-Z0-9?><;,{}[\]\-_+=!@#$%\^&*|']*$]/", "", $string);
        $string = strip_tags($string);
        $string = stripslashes($string);
        $string = str_replace(['"',"'"], "", $string);
        $string = htmlentities($string);
        // $string = preg_replace('/([0-9]+).*/', '$1', $string);

        return $string;
    }
}