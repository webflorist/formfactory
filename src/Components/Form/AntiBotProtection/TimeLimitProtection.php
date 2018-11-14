<?php

namespace Nicat\FormFactory\Components\Form\AntiBotProtection;

use Nicat\FormFactory\Components\Form\Form;
use Nicat\FormFactory\Components\Helpers\ErrorContainer;
use Nicat\FormFactory\Exceptions\MandatoryOptionMissingException;

class TimeLimitProtection
{

    /**
     * Handle setting the session-info for timeLimit-protection, if timeLimit-protection is enabled in the config.
     *
     * @param string $requestObject
     */
    public static function setUp(string $requestObject)
    {

        // We save the generationTime for this form in the session.
        // It will be read out by the TimeLimitValidator after submitting the form.
        session()->put('formfactory.generation_time.' . $requestObject, time());
    }

    /**
     * The registered validator for the _timeLimit field.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public static function validate($attribute, $value, $parameters, $validator)
    {

        // If timeLimit-protection is not enabled in the config, we immediately return true.
        if (!config('formfactory.time_limit.enabled')) {
            return true;
        }

        // Establish time-limit from $parameters or config.
        $timeLimit = self::getTimeLimitFromRuleParams($parameters);

        // We need to get the name of the last resolved form-request-object from session.
        if (!session()->has('formfactory.last_form_request_object')) {
            return false;
        }

        $requestObject = session()->get('formfactory.last_form_request_object');

        // We expect the generation-time of the form in the session and return a false if it is not present.
        $generationTimeSessionKey = 'formfactory.generation_time.' . $requestObject;
        if (!session()->has($generationTimeSessionKey)) {
            return false;
        }

        // Finally get the generation-time of the form from the session
        // and validate if the time-limit was honored.
        $generationTime = session()->get($generationTimeSessionKey);
        if ((time() - $generationTime) < $timeLimit) {
            return false;
        }

        return true;

    }

    /**
     * Get default-limit to be used for timeLimit-protection.
     * Returns $parameters[0], if set.
     * Otherwise returns default-limit set in config.
     *
     * @param $parameters
     * @return \Illuminate\Config\Repository|mixed
     */
    public static function getTimeLimitFromRuleParams(array $parameters)
    {
        if (isset($parameters[0]) && is_numeric($parameters[0])) {
            return $parameters[0];
        }
        return config('formfactory.time_limit.default_limit');
    }


}