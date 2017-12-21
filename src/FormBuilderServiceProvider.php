<?php

namespace Nicat\FormBuilder;

use Illuminate\Support\ServiceProvider;
use Nicat\HtmlBuilder\HtmlBuilder;

class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param HtmlBuilder $htmlBuilder
     * @return void
     * @throws \Nicat\HtmlBuilder\Exceptions\DecoratorNotFoundException
     */
    public function boot(HtmlBuilder $htmlBuilder)
    {

        // Publish the config.
        $this->publishes([
            __DIR__.'/config/formbuilder.php' => config_path('formbuilder.php'),
        ]);

        // Merge the config.
        $this->mergeConfigFrom(__DIR__.'/config/formbuilder.php', 'formbuilder');

        // Register included decorators.
        $htmlBuilder->decorators->registerFromFolder(
            'Nicat\FormBuilder\Decorators\General',
            __DIR__.'/Decorators/General'
        );
        $htmlBuilder->decorators->registerFromFolder(
            'Nicat\FormBuilder\Decorators\Bootstrap\v3',
            __DIR__.'/Decorators/Bootstrap/v3'
        );

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FormBuilder::class, function()
        {
            return new FormBuilder();
        });

    }
}