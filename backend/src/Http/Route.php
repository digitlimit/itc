<?php

namespace ITC\Insurance\Http;

use ITC\Insurance\Controllers\InsuranceController;

/**
 * Just a simple routing system
 */
class Route
{
    protected static array $routes = [
        '/insurance' => InsuranceController::class
    ];

    /**
     * Resolve routes
     */
    public static function resolve()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");

        $route = strtok($_SERVER["REQUEST_URI"], '?');

        if(!isset(self::$routes[$route])) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }

        $controller = new self::$routes[$route];
        $controller->outputJson();
        die;
    }
}