<?php

namespace Core\Routes;

use Core\Middleware\MiddlewareInterface;

class Route
{
    private $uri;
    private $action;
    private $middlewares = [];

    public function __construct($uri, $action)
    {
        $this->uri = $uri;
        $this->action = $action;
    }

    public function middleware(array $middlewareClasses): self
    {
        $this->middlewares = array_merge($this->middlewares, $middlewareClasses);
        return $this;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getMiddlewares()
    {
        return $this->middlewares;
    }
}
