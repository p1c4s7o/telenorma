<?php

namespace App;

use DirectoryIterator;

class Config
{

    public function __construct(string $path)
    {
        $dir = new DirectoryIterator($path);
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isFile() && ($ext = $fileinfo->getExtension()) === 'php') {
                $name = str_replace('.', '_', str_replace(".$ext", '', $fileinfo->getFilename()));
                $this->_config[$name] = require_once $fileinfo->getPathname();
            }
        }
    }

    private array $_config = [];

    public function get($name)
    {
        if(!isset($this->_config[$name]))
            return null;
        return $this->_config[$name];
    }
}