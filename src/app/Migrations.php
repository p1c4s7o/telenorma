<?php

namespace App;

use DirectoryIterator;

class Migrations
{
    public function __construct(Db $db, string $path)
    {
        $dir = new DirectoryIterator($path);
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isFile() && ($ext = $fileinfo->getExtension()) === 'php') {
                $call_array = require_once $fileinfo->getPathname();
                if(is_array($call_array) || count($call_array) > 0)
                    array_map(function ($sql_cmd) use ($db) {
                        $db->exec($sql_cmd);
                    }, $call_array);
            }
        }
    }
}