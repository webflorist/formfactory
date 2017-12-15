<?php

namespace Nicat\FormBuilder;

use Illuminate\Support\ServiceProvider;

class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        // Publish the config.
        $this->publishes([
            __DIR__.'/config/formbuilder.php' => config_path('formbuilder.php'),
        ]);

        // Merge the config.
        $this->mergeConfigFrom(__DIR__.'/config/formbuilder.php', 'formbuilder');

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