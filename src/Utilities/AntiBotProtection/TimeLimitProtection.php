<?php

namespace Nicat\FormFactory\Utilities\AntiBotProtection;

use Nicat\FormFactory\Components\Additional\ErrorContainer;
use Nicat\FormFactory\Components\Form;
use Nicat\FormFactory\Exceptions\MandatoryOptionMissingException;

class TimeLimitProtection
{

    /**
     * Handle setting the session-info for timeLimit-protection, if timeLimit-protection is enabled in the config.
     *
     * @param Form $form
     * @throws MandatoryOptionMissingException
     */
    public static function setUp(Form $form)
    {

        // If timeLimit-protection is not enabled in the config, there is nothing to do here.
        if (!config('formfactory.time_limit.enabled')) {
            return;
        }

        // If TimeLimit is not enabled for the specific form, there is also nothing to do here.
        if (count($form->rules->getRulesForField('_timeLimit')) === 0) {
            return;
        }

        // TimeLimit-protection only works, if a request-object was stated via the requestObject() method,
        // so we throw an exception, if this was not the case.
        if (is_null($form->requestObject)) {
            throw new MandatoryOptionMissingException(
                'The form with ID "' . $form->attributes->id . '" should be protected by a time-limit, ' .
                'but no request-object was stated via the Form::open()->requestObject() method. ' .
                'TimeLimit-protection only works if this is the case.'
            );
        }

        // Now we save the generationTime for this form in the session.
        // It will be read out by the TimeLimitValidator after submitting the form.
        session()->put('formfactory.generation_time.' . $form->requestObject, time());

        // We also add an errorContainer to display any errors for '_timeLimit' to the form.
        $errorContainer = new ErrorContainer();
        $errorContainer->addErrorField('_timeLimit');
        $form->appendContent($errorContainer);
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