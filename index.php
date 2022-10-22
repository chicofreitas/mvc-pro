<?php
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
 * Redirects to the specified path.
 * 
 * @param $path string
 */
function redirectTo($path)
{
    header("Location: {$path}", $replace = true, $code = 301);
    exit;
}

/**
 * 
 * 
 * @var Int
 */
function abort($code){
    global $routes;
    header('HTTP/1.1 500 Internal Server Error');
    $routes[$code]();
}

/**
 * 
 * @var Function
 */
set_error_handler(function(){
    abort(500);
});

/**
 * 
 * @var Function
 */
set_exception_handler(function(){
    abort(500);
});

/**
 * Defining our route system.
 * 
 * @var Array
 */
$routes = [
    'GET' => [
        '/' => fn() => print <<<HTML
            <!Doctype html>
            <html lang="en">
                <body>
                    hello man!!
                </body>
            </html>
        HTML,
        '/home' => fn() => redirectTo('/'),
        '/has-server-error' => fn() => throw new Exception(),
        '/has-validation-error' => fn() => abort(400)
    ],
    'POST' => [],
    'PATCH' => [],
    'PUT' => [],
    'DELETE' => [],
    'HEAD' => [],
    '404' => fn() => include(__DIR__ . '/includes/404.php'),
    '400' => fn() => include(__DIR__ . '/includes/400.php'),
    '500' => fn() => include(__DIR__ . '/includes/500.php')
];

/**
 * 
 * @var Array
 */
$paths = array_merge(
    array_keys($routes['GET']),
    array_keys($routes['POST']),
    array_keys($routes['PATCH']),
    array_keys($routes['PUT']),
    array_keys($routes['DELETE']),
    array_keys($routes['HEAD']),
);

/**
 * Handling requests.
 */
if ( isset($routes[$requestMethod], $routes[$requestMethod][$requestPath]) ) {

    $routes[$requestMethod][$requestPath]();

} else if( in_array($requestPath, $paths) ){

    $routes['400']();

} else {

    $routes['404']();

}