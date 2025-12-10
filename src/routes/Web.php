<?php

namespace routes;

use App\Action\User;
use App\Boot;
use App\Route;
use App\View;

class Web extends Route
{

    public function __construct()
    {
        parent::__construct();
        $this->set('');
    }

    public function index()
    {
        View::show(__ROOT__ . 'views/index');
    }

    public function after_register(Boot $boot): void
    {
        static::$_map['GET'] = [
            '/' => 'index'
        ];
    }
}