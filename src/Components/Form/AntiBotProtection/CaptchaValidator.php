<?php

namespace Nicat\FormFactory\Components\Form\AntiBotProtection;

use Illuminate\Cache\RateLimiter;
use Illuminate\Validation\Validator;
use Nicat\FormFactory\Components\Form\Form;
use Nicat\FormFactory\Exceptions\MandatoryOptionMissingException;
use Nicat\FormFactory\FormFactory;

class CaptchaValidator
{

    /**
     * Validates a captcha-secured request.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param Validator $validator
     * @return bool
     */
    public static function validate($attribute, $value, $parameters, $validator)
    {

        // If captcha-protection is not enabled in the config, we immediately return true.
        if (!config('formfactory.captcha.enabled')) {
            return true;
        }

        // We need to get the name of the last resolved form-request-object from session.
        if (!session()->has('formfactory.last_form_request_object')) {
            return false;
        }

        $formRequestClass = session()->get('formfactory.last_form_request_object');

        $captchaProtection = (new CaptchaProtection($formRequestClass, $parameters));
        $isValid =  $captchaProtection->validate($value);

        $captchaProtection->hit();

        return $isValid;

    }

}