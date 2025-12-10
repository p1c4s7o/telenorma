<?php

namespace App;

use DirectoryIterator;
use Exception\Forbidden;
use Exception\MethodNotAllowed;
use Exception\RouteNotFound;

class Router
{
    private static array $_routes = [];

    public function __construct(string $dir, string $route_ns)
    {
        $dir = new DirectoryIterator($dir);
        foreach ($dir as $fileInfo) {
            if ($fileInfo->isFile() && ($ext = $fileInfo->getExtension()) === 'php') {
                $name = str_replace('.', '_', str_replace(".$ext", '', $fileInfo->getFilename()));
                require_once $fileInfo->getPathname();
                $class_name = "$route_ns$name";
                if (!class_exists($class_name))
                    throw new \RuntimeException('Not found class ' . $class_name);
                static::$_routes[strtolower($name)] = new $class_name();
            }
        }
    }

    /**
     *
     * @param string $request_uri
     * @param string $method
     * @return Route|null
     * @throws Forbidden
     * @throws RouteNotFound
     */
    private function routeIterator(string $request_uri, string $method): ?Route
    {
        $found = false;


        foreach (static::$_routes as $route => $router) {
            if ($route === 'web')
                continue;

            /** @var Route $router */
            if (preg_match("#^/{$route}(/.*)$#i", $request_uri)) {
                $found = true;
                if (!$router->allow_request($request_uri, $method))
                    continue;
                if (str_contains($request_uri, '?')) {
                    if (!$router->allow_query_params()) {
                        throw new RouteNotFound('Page not found');
                    }
                }

                return $router;
            }
        }

        if ($found)
            throw new Forbidden('Forbidden');

        if (isset(static::$_routes['web'])) {
            if (static::$_routes['web']->allow_request($request_uri, $method)) {
                return static::$_routes['web'];
            }
        }

        return null;
    }

    /**
     * @param Boot $boot
     * @return void
     * @throws Forbidden
     * @throws RouteNotFound|MethodNotAllowed
     */
    public function listen(Boot $boot): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        $router = $this->routeIterator($uri, $method);
        if (!($router instanceof Route))
            throw new RouteNotFound('Endpoint not found');
        $router->after_register($boot);
        $router->init_services($boot);
        $router->provider([
            'method' => $method,
            'uri' => $uri
        ], $boot);
    }
}