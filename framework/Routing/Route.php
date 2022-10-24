<?php

namespace Framework\Routing;

class Route
{
    /**
     * @var string $method
     */
    protected string $method;

    /**
     * @var string $path
     */
    protected string $path;

    /**
     * @var callable $handler
     */
    protected string $handler;

    /**
     * @var array $parameters
     */
    protected array $parameters;

    /**
     * 
     * @param $method string
     * @param $path string
     * @param $handler callable
     */
    public function __construct(string $method, string $path, callable $handler)
    {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
    }

    /**
     * 
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * 
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * @todo
     */
    public function matches(string $method, string $path) : bool
    {
        return $this->method === $method && $this->path === $path;
    }

    /**
     * 
     */
    public function dispatch()
    {
        return call_user_func($this->handler);
    }

    /**
     * @return array $parameters
     */
    public function parameters() : array
    {
        return $this->parameters;
    }


}
