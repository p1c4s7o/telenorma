<?php

namespace App;

use Exception\Forbidden;
use Exception\MethodNotAllowed;
use Exception\RouteNotFound;

class Route
{
    private string $endpoint = '';

    protected static array $_map = [];
    protected static array $_err_map = [];

    public function __construct()
    {
        $this->endpoint = strtolower((new \ReflectionClass(static::class))->getShortName());
    }

    protected function set(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    public function allow_request(string $request_uri, string $method = 'GET'): bool
    {
        return preg_match('#^/' . $this->endpoint . '([a-zA-Z0-9_?/-]+)?$#i', $request_uri);
    }

    public function allow_query_params(): bool
    {
        return false;
    }

    public function provider(array $params, Boot $boot): void
    {
        if(!isset(static::$_map[$params['method']]))
            throw new MethodNotAllowed('Method not allowed');

        $endpoint = $this->endpoint === '' ? '' : '/' . $this->endpoint;
        foreach (static::$_map[$params['method']] as $route => $method) {
            if(preg_match('#^' . $endpoint . $route . '$#i', $params['uri'], $matches)) {
                $params['vars'] = $matches;
                $this->$method($params, $boot);
                return;
            }
        }

        throw new RouteNotFound();
    }

    public function after_register(Boot $boot): void
    {
        //
    }

    public function init_services(Boot $boot): void
    {
        //
    }

    public function json()
    {
        try {
            return json_decode(file_get_contents("php://input"), true);
        } catch (\Exception $exception) {
            return [];
        }
    }
}