<?php

namespace Framework\Routing;

use Framework\Routing\Route;
use Exception;

class Router{

    /**
     * @var array $routes
     */
    protected array $routes = [];

    /**
     * @var array $errorHandler
     */
    protected array $errorHandlers = [];

    /**
     * @var Route $current
     */
    protected Route $current;

    /**
     * Adiciona uma Rota ao array $routes
     * 
     * @param $method string
     * @param $path string
     * @param $handler callable
     * 
     * @return Route
     */
    public function add(string $method, string $path, callable $handler) : Route
    {
        $route = $this->routes[] = new Route($method, $path, $handler);
        return $route;
    }

    /**
     * 
     */
    public function dispatch()
    {
        $paths = $this->paths();

        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestPath = $_SERVER['REQUEST_URI'] ?? '/';

        $matching = $this->match($requestMethod, $requestPath);

        if ($matching) {
            try {
                
                return $matching->dispatch();

            } catch (\Throwable $th) {
                //throw $th;
                return $this->dispatchError();

            }
        }

        if (in_array($requestPath, $paths)) {
            return $this->dispatchNotAllowed();
        }

        return $this->dispatchNotFound();
    }

    /**
     * Retorna um array $paths
     * 
     * @return array $paths
     */
    public function paths() : array
    {
        $paths = [];

        foreach ($this->routes as $route) {
            $paths[] = $route->getPath();
        }

        return $paths;
    }

    /**
     * Retorna uma Route caso ela exista no array $routes
     * 
     * @param string $method
     * @param string $path
     * 
     * @return mixed
     */
    public function match(string $method, string $path) : ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }
        
        return null;
    }

    /**
     * 
     * 
     * @param int $code 
     * @param callable $handler
     */
    public function errorHandler(int $code, callable $handler)
    {
        $this->errorHandlers[$code] = $handler;
    }

    /**
     * 
     */
    public function dispatchNotAllowed()
    {
        $this->errorHandlers[400] ??= fn() => "not allowerd";
        return $this->errorHandlers[400]();
    }

    /**
     * 
     */
    public function dispatchNotFound()
    {
        $this->errorHandlers[404] ??= fn() => "not found";
        return $this->errorHandlers[404]();
    }

    /**
     * 
     */
    public function dispatchError()
    {
        $this->errorHandlers[500] ??= fn() => "server error";
        return $this->errorHandler[500]();
    }

    /**
     * 
     */
    public function redirect(string $path)
    {
        header("Location: {$path}", $replace = true, $code = 301);
        exit;
    }

    /**
     * 
     * 
     * @return mixed
     */
    public function current() : ?Route
    {
        return $this->current;
    }

    /**
     * 
     * @param string $name
     * @param array $parameters
     * @return string
     */
    public function route(string $name, array $parameters = []) : string
    {
        foreach ($this->routes as  $route) {
            if ($route->name() === $name) {
                $finds = [];
                $replaces = [];
                
                foreach ($parameters as $key => $value) {
                    array_push($finds, "{{$key}}");
                    array_push($replaces, $value);
                    array_push($finds, "{{$key}?}");
                    array_push($replaces, $value);
                }

                $path = $route->path();
                $path = str_replace($finds, $replaces, $path);
                $path = preg_replace('#{[^}]+}#', '',$path);
                
                return $path;
            }
        }

        throw new Exception('no route with that name', 1);
        
    }
}
