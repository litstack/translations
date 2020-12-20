<?php

namespace Litstack\Translations;

use Ignite\Support\Facades\Config;
use Ignite\Support\Facades\Nav;
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
            $config = Config::get(LangConfig::class)
        );

        Nav::preset([
            'nav', LangConfig::class,
        ], [
            'link'  => lit()->url($config->route_prefix),
            'title' => $config->names['plural'],
        ]);
    }
}
