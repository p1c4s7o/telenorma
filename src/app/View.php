<?php

namespace App;

class View
{
    public static function json(array $res, int $code = 200): void
    {
        header('Content-type: application/json; charset=utf-8');
        http_response_code($code);
        exit(json_encode($res));
    }

    public static function show(string $file, int $code = 200):void
    {
        $filename = $file . '.html';
        http_response_code($code);

        // TODO validation path
        if(file_exists($filename) && is_file($filename))
            exit(file_get_contents($filename));
    }
}