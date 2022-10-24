<?php

use Framework\Routing\Router;

/**
 * This file is responsible to add routes into the Router's array $routes
 * 
 * @param Router $router
 */
return function(Router $router){
    $router->add(
        'GET', '/',
        fn() => 'Hello, World!'
    );

    $router->add(
        'GET', '/home',
        fn() => $router->redirect('/')
    );

    $router->add(
        'GET', '/has-server-error',
        fn() => throw new Exception()
    );

    $router->add(
        'GET', '/has-validation-error',
        fn() => $router->dispatchNotAllowed()
    );

    $router->errorHandler(404, fn() => 'Nada encontrado, sorry!');

    $router->add(
        'GET', '/posts/{id}',
        function () use ($router) {

            $parameters = $router->current()->parameters();
            
            return "Product {$parameters['id']}";
        }
    );
};