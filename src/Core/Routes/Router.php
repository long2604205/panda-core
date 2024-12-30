<?php

namespace PandaCore\Core\Routes;

use Exception;
use PandaCore\Core\Middleware\MiddlewareInterface;
use PandaCore\Core\Http\Request\Request;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    public function get($uri, $action): Route
    {
        return $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action): Route
    {
        return $this->addRoute('POST', $uri, $action);
    }

    public function put($uri, $action): Route
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    public function delete($uri, $action): Route
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    private function addRoute($method, $uri, $action): Route
    {
        $route = new Route($uri, $action);
        $this->routes[$method][$uri] = $route;
        return $route;
    }

    /**
     * @throws Exception
     */
    public function resolve($method, $uri, Request $request)
    {
        $route = $this->routes[$method][$uri] ?? null;

        if (!$route) {
            throw new Exception("Route not found", 404);
        }
        
        foreach ($route->getMiddlewares() as $middlewareClass) {
            $middleware = new $middlewareClass();
            if ($middleware instanceof MiddlewareInterface) {
                $middleware->handle($request, function() {});
            } else {
                throw new Exception("Invalid Middleware", 500);
            }
        }

        $action = $route->getAction();

        if (is_callable($action)) {
            return $action($request);
        }

        if (is_array($action)) {
            $controllerName = $action[0];
            $method = $action[1];

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $method)) {
                    return $controller->$method($request);
                } else {
                    throw new Exception("Method not found", 404);
                }
            } else {
                throw new Exception("Controller not found", 404);
            }
        }

        throw new Exception("Invalid action", 500);
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
