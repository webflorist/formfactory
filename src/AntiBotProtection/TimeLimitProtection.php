<?php

namespace Nicat\FormBuilder\AntiBotProtection;

use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\Elements\HiddenInputElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\FormBuilder\Exceptions\MandatoryOptionMissingException;

class TimeLimitProtection
{

    /**
     * Handle setting the session-info for timeLimit-protection, if timeLimit-protection is enabled in the config.
     *
     * @param FormElement $form
     * @throws MandatoryOptionMissingException
     */
    public static function setUp(FormElement $form)
    {

        // If timeLimit-protection is not enabled in the config, there is nothing to do here.
        if (!config('formbuilder.time_limit.enabled')) {
            return;
        }

        // If TimeLimit is not enabled for the specific form, there is also nothing to do here.
        if (count($form->getRulesForField('_timeLimit')) === 0) {
            return;
        }

        // TimeLimit-protection only works, if a request-object was stated via the requestObject() method,
        // so we throw an exception, if this was not the case.
        if (is_null($form->requestObject)) {
            throw new MandatoryOptionMissingException(
                'The form with ID "' . $form->attributes->getValue('id') . '" should be protected by a time-limit, ' .
                'but no request-object was stated via the Form::open()->requestObject() method. ' .
                'TimeLimit-protection only works if this is the case.'
            );
        }

        // Now we save the generationTime for this form in the session.
        // It will be read out by the TimeLimitValidator after submitting the form.
        session()->put('formbuilder.generation_time.' . $form->requestObject, time());

        // We also add a hidden input called '_timeLimit' to the form.
        // The only purpose for this is, that any timeLimit-errors are displayed at the beginning of the form.

        // TODO: should not be a TextInputElement.
        $form->appendChild(
            (new TextInputElement())
                ->name('_timeLimit')
                ->value('')
        );
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
        if (!config('formbuilder.time_limit.enabled')) {
            return true;
        }

        // Establish time-limit from $parameters or config.
        $timeLimit = self::getTimeLimitFromRuleParams($parameters);

        // We need to get the name of the last resolved form-request-object from session.
        if (!session()->has('formbuilder.last_form_request_object')) {
            return false;
        }

        $requestObject = session()->get('formbuilder.last_form_request_object');

        // We expect the generation-time of the form in the session and return a false if it is not present.
        $generationTimeSessionKey = 'formbuilder.generation_time.' . $requestObject;
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
        return config('formbuilder.time_limit.default_limit');
    }


}