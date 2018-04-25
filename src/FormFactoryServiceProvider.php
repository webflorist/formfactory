<?php

namespace Nicat\FormFactory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\ServiceProvider;
use Nicat\FormFactory\Utilities\AntiBotProtection\HoneypotProtection;
use Nicat\FormFactory\Utilities\AntiBotProtection\TimeLimitProtection;
use Nicat\FormFactory\Utilities\AntiBotProtection\CaptchaProtection;
use Nicat\HtmlFactory\HtmlFactory;
use Route;
use Validator;

class FormFactoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     * @throws \Nicat\HtmlFactory\Exceptions\DecoratorNotFoundException
     */
    public function boot()
    {

        // Publish the config.
        $this->publishes([
            __DIR__ . '/config/formfactory.php' => config_path('formfactory.php'),
        ]);

        // Publish public stuff.
        $this->publishes([
            __DIR__.'/public' => public_path('vendor/nicat/formfactory'),
        ], 'public');

        // Load translations.
        $this->loadTranslationsFrom(__DIR__ . "/resources/lang", "Nicat-FormFactory");

        // Register included decorators.
        $this->registerHtmlFactoryDecorators();

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
        $this->app->singleton(FormFactory::class, function () {
            return new FormFactory();
        });

        // Merge the config.
        $this->mergeConfigFrom(__DIR__ . '/config/formfactory.php', 'formfactory');

    }

    /**
     * Register included decorators with HtmlFactory.
     *
     * @throws \Nicat\HtmlFactory\Exceptions\DecoratorNotFoundException
     */
    private function registerHtmlFactoryDecorators()
    {
        /** @var HtmlFactory $htmlFactory */
        $htmlFactory = app(HtmlFactory::class);
        $htmlFactory->decorators->registerFromFolder(
            'Nicat\FormFactory\Decorators\General',
            __DIR__ . '/Decorators/General'
        );
        $htmlFactory->decorators->registerFromFolder(
            'Nicat\FormFactory\Decorators\Bootstrap\v3',
            __DIR__ . '/Decorators/Bootstrap/v3'
        );
        $htmlFactory->decorators->registerFromFolder(
            'Nicat\FormFactory\Decorators\Bootstrap\v4',
            __DIR__ . '/Decorators/Bootstrap/v4'
        );
    }

    /**
     * Registers a resolver-callback to store the last used FormRequest-class in the session.
     * This is used by the CaptchaValidator and TimeLimitValidator to retrieve the corresponding FormRequest.
     */
    private function registerFormRequestResolverCallback()
    {
        app()->resolving(FormRequest::class, function ($object) {
            session()->put('formfactory.last_form_request_object', get_class($object));
        });
    }

    /**
     * Register the captcha-validator, if captcha-protection is enabled in the config.
     */
    private function registerCaptchaValidator()
    {
        if (config('formfactory.captcha.enabled')) {

            Validator::extendImplicit('captcha', CaptchaProtection::class . '@validate');

            // We deliver the error configured in the htmlfactory-language-file.
            Validator::replacer('captcha', function ($message, $attribute, $rule, $parameters) {
                return trans('Nicat-FormFactory::formfactory.captcha_error');
            });
        }
    }

    /**
     * Register the timeLimit-validator, if timeLimit-protection is enabled in the config.
     */
    private function registerTimeLimitValidator()
    {
        if (config('formfactory.time_limit.enabled')) {

            Validator::extendImplicit('timeLimit', TimeLimitProtection::class . '@validate');

            // We deliver the error configured in the htmlfactory-language-file and replace the time-limit.
            Validator::replacer('timeLimit', function ($message, $attribute, $rule, $parameters) {

                return trans('Nicat-FormFactory::formfactory.time_limit_error', [
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
        if (config('formfactory.honeypot.enabled')) {

            Validator::extendImplicit('honeypot', HoneypotProtection::class . '@validate');

            // We deliver the error configured in the htmlfactory-language-file.
            Validator::replacer('honeypot', function ($message, $attribute, $rule, $parameters) {
                return trans('Nicat-FormFactory::formfactory.honeypot_error');
            });
        }
    }

    /**
     * Register the routes used for ajax-validation, if ajax-validation is enabled in the config.
     */
    private function registerAjaxValidationRoutes()
    {
        if (config('formfactory.ajax_validation.enabled')) {
            Route::middleware('web')->post('/formfactory_validation', 'Nicat\FormFactory\Utilities\AjaxValidation\AjaxValidationController@process');
            Route::middleware('web')->put('/formfactory_validation', 'Nicat\FormFactory\Utilities\AjaxValidation\AjaxValidationController@process');
            Route::middleware('web')->delete('/formfactory_validation', 'Nicat\FormFactory\Utilities\AjaxValidation\AjaxValidationController@process');
        }
    }
}