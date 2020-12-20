<?php

namespace Litstack\Translations;

use Ignite\Support\Facades\Lang;
use Illuminate\Translation\TranslationServiceProvider;
use Illuminate\Translation\Translator;
use Litstack\Translations\Commands\ClearCommand;

class TranslationsServiceProvider extends TranslationServiceProvider
{
    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        Lang::addPath(__DIR__.'/../lang');
    }

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->config();
        $this->app->register(RouteServiceProvider::class);
        $this->registerLoader();
        $this->registerClearCommand();

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }

    /**
     * Register and merge config.
     *
     * @return void
     */
    protected function config()
    {
        $this->publishes([
            __DIR__.'/../config' => config_path(),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/lit-translations.php',
            'lit-translations'
        );
    }

    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new DatabaseLoader(
                $app['cache'], $app['files'], $app['path.lang']
            );
        });
    }

    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerClearCommand()
    {
        $this->app->singleton('lit.commands.lang.clear', function ($app) {
            return new ClearCommand($app['translation.loader']);
        });
        $this->commands(['lit.commands.lang.clear']);
    }
}
