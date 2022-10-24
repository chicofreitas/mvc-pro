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
     * 
     * 
     * @param string $method
     * @param string $path
     * 
     * @return bool
     */
    public function matches(string $method, string $path) : bool
    {
        if ($this->method === $method && $this->path === $path  ) {
            return true;
        }

        // Caso não haja $path valido, vamos procurar por named routes.

        $parametersNames = [];

        $pattern = $this->normalizePath($this->path);

        $pattern = preg_replace_callback(
            '#{([^}]+)}/#',
            function(array $found) use (&$parametersNames){

                array_push(
                    $parametersNames, rtrim($found[1], '?') // remove a ? do final da string caso ela exista.
                );

                if (str_ends_with($found[1], '?')) {
                    return '([^/]*)(?:/?)'; //
                }

                return '([^/]+)';
            },
            $pattern
        );

        if (!str_contains($pattern, '+') && !str_contains($pattern, '*')) {
            return false;
        }
        
        preg_match_all("#{$pattern}#", $this->normalizePath($path), $matches);

        $parameterValues = [];

        if (count($matches[1]) > 0) {
            
            foreach ($matches[1] as $value) {
                array_push($parameterValues, $value);
            }

            $emptyValues = array_fill(
                0, count($parameterValues),null
            );

            $parameterValues += $emptyValues;

            $this->parameters = array_combine($parametersNames, $parameterValues);

            return true;
        }

        return false;
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

    /**
     * 
     * 
     * @param string $path
     * @return string
     */
    protected function normalizePath(string $path) : string
    {
        $path = trim($path, '/');
        $path = "/{$path}/";

        $path = preg_replace("/[\/]{2,}/", '/', $path);

        return $path;
    }
}
