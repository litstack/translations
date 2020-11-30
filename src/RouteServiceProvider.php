<?php

namespace Litstack\Translations;

use Ignite\Support\Facades\Config;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Map routes.
     *
     * @return void
     */
    public function map()
    {
        app('lit.crud.router')->routes(
            Config::get(LangConfig::class)
        );
    }
}
