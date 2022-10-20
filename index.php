<?php
//
//var_dump( getenv('PHP_ENV'), $_SERVER, $_REQUEST);

/**
 * 
 * @var string
 */
$requestMethod = $_SERVER['REQUEST_METHOD'];

/**
 * 
 * @var string
 */
$requestPath = $_SERVER['REQUEST_URI'];

/**
 *
 *  
 */
if ($requestMethod === 'GET' and $requestPath === '/') {
    print 'Hello, World!';
}else{
    print '404 Not Found.';
}