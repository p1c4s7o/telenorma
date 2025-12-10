<?php

if (!function_exists('env')) {
    function env(string $variable, $def_value = null): mixed
    {
        if (strlen(trim($variable)) < 1)
            return $def_value;

        $var = getenv($variable);

        if (!$var)
            return $def_value;
        return $var;
    }
}
