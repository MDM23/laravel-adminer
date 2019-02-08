<?php

namespace MDM23\LaravelAdminer;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;

class AdminerServiceProvider extends RouteServiceProvider
{
    public function map(Router $router)
    {
        $router->any("adminer", function () {
            require_once __DIR__ . "/../../resources/adminer.php";
        });
    }
}
