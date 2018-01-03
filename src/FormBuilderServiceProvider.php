<?php

namespace Nicat\FormBuilder;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\ServiceProvider;
use Nicat\FormBuilder\AntiBotProtection\HoneypotProtection;
use Nicat\FormBuilder\AntiBotProtection\TimeLimitProtection;
use Nicat\FormBuilder\AntiBotProtection\CaptchaProtection;
use Nicat\HtmlBuilder\HtmlBuilder;
use Route;
use Validator;

class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     * @throws \Nicat\HtmlBuilder\Exceptions\DecoratorNotFoundException
     */
    public function boot()
    {

        // Publish the config.
        $this->publishes([
            __DIR__ . '/config/formbuilder.php' => config_path('formbuilder.php'),
        ]);

        // Merge the config.
        $this->mergeConfigFrom(__DIR__ . '/config/formbuilder.php', 'formbuilder');

        // Load translations.
        $this->loadTranslationsFrom(__DIR__ . "/resources/lang", "Nicat-FormBuilder");

        // Register included decorators.
        $this->registerHtmlBuilderDecorators();

        // Every time a FormRequest is resolved, we store the last used FormRequest-class in the session.
        // This is used by the CaptchaValidator and TimeLimitValidator to retrieve the corresponding FormRequest.
        $this->registerFormRequestResolverCallback();

        // Register the captcha-validator, if captcha-protection is enabled in the config.
        $this->registerCaptchaValidator();

        // Register the timeLimit-validator, if timeLimit-protection is enabled in the config.
        $this->registerTimeLimitValidator();

        // Register the honeypot-validator, if honeypot-protection is enabled in the config.
        $this->registerHoneypotValidator();

        // Register the routes used for ajax-validation, if ajax-validation is enabled in the config.
        $this->registerAjaxValidationRoutes();

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FormBuilder::class, function () {
            return new FormBuilder();
        });

    }

    /**
     * Register included decorators with HtmlBuilder.
     *
     * @throws \Nicat\HtmlBuilder\Exceptions\DecoratorNotFoundException
     */
    private function registerHtmlBuilderDecorators()
    {
        /** @var HtmlBuilder $htmlBuilder */
        $htmlBuilder = app(HtmlBuilder::class);
        $htmlBuilder->decorators->registerFromFolder(
            'Nicat\FormBuilder\Decorators\General',
            __DIR__ . '/Decorators/General'
        );
        $htmlBuilder->decorators->registerFromFolder(
            'Nicat\FormBuilder\Decorators\Bootstrap\v3',
            __DIR__ . '/Decorators/Bootstrap/v3'
        );
    }

    /**
     * Registers a resolver-callback to store the last used FormRequest-class in the session.
     * This is used by the CaptchaValidator and TimeLimitValidator to retrieve the corresponding FormRequest.
     */
    private function registerFormRequestResolverCallback()
    {
        app()->resolving(FormRequest::class, function ($object) {
            session()->put('formbuilder.last_form_request_object', get_class($object));
        });
    }

    /**
     * Register the captcha-validator, if captcha-protection is enabled in the config.
     */
    private function registerCaptchaValidator()
    {
        if (config('formbuilder.captcha.enabled')) {

            Validator::extendImplicit('captcha', CaptchaProtection::class . '@validate');

            // We deliver the error configured in the htmlbuilder-language-file.
            Validator::replacer('captcha', function ($message, $attribute, $rule, $parameters) {
                return trans('Nicat-FormBuilder::formbuilder.captcha_error');
            });
        }
    }

    /**
     * Register the timeLimit-validator, if timeLimit-protection is enabled in the config.
     */
    private function registerTimeLimitValidator()
    {
        if (config('formbuilder.time_limit.enabled')) {

            Validator::extendImplicit('timeLimit', TimeLimitProtection::class . '@validate');

            // We deliver the error configured in the htmlbuilder-language-file and replace the time-limit.
            Validator::replacer('timeLimit', function ($message, $attribute, $rule, $parameters) {

                return trans('Nicat-FormBuilder::formbuilder.time_limit_error', [
                    'timeLimit' => TimeLimitProtection::getTimeLimitFromRuleParams($parameters)
                ]);
            });
        }
    }

    /**
     * Register the honeypot-validator, if honeypot-protection is enabled in the config.
     */
    private function registerHoneypotValidator()
    {
        if (config('formbuilder.honeypot.enabled')) {

            Validator::extendImplicit('honeypot', HoneypotProtection::class . '@validate');

            // We deliver the error configured in the htmlbuilder-language-file.
            Validator::replacer('honeypot', function ($message, $attribute, $rule, $parameters) {
                return trans('Nicat-FormBuilder::formbuilder.honeypot_error');
            });
        }
    }

    /**
     * Register the routes used for ajax-validation, if ajax-validation is enabled in the config.
     */
    private function registerAjaxValidationRoutes()
    {
        if (config('formbuilder.ajax_validation.enabled')) {
            Route::middleware('web')->post('/formbuilder_validation', 'Nicat\FormBuilder\AjaxValidation\AjaxValidationController@process');
            Route::middleware('web')->put('/formbuilder_validation', 'Nicat\FormBuilder\AjaxValidation\AjaxValidationController@process');
            Route::middleware('web')->delete('/formbuilder_validation', 'Nicat\FormBuilder\AjaxValidation\AjaxValidationController@process');
        }
    }
}