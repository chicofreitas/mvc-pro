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
 * @param $requestMethod string
 * @param $requestPath string
 * @return Heredoc|string
 */
if ($requestMethod === 'GET' and $requestPath === '/') {
    print <<<HTML
        <!Doctype html>
        <html lang="en">
            <body>
                hello man!!
            </body>
        </html>
    HTML;
}else if($requestPath === '/home'){
    redirectTo('/');
}else{
    include(__DIR__.'/includes/404.php');
}