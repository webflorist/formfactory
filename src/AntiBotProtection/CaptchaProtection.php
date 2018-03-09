<?php

namespace Nicat\FormBuilder\AntiBotProtection;

use Illuminate\Cache\RateLimiter;
use Nicat\FormBuilder\Elements\FormElement;
use Nicat\FormBuilder\Elements\TextInputElement;
use Nicat\FormBuilder\Exceptions\MandatoryOptionMissingException;

class CaptchaProtection
{

    /**
     * Handle setting the session-info and generation of the captcha-field, if captcha-protection is enabled in the config.
     *
     * @param FormElement $form
     * @throws MandatoryOptionMissingException
     */
    public static function setUp(FormElement $form)
    {

        // If captcha-protection is not enabled in the config, there is nothing to do here.
        if (!config('formbuilder.captcha.enabled')) {
            return;
        }

        // We retrieve the captcha-rules defined for this form.
        $captchaRules = $form->rules->getRulesForField('_captcha');

        // If no captcha-rules are defined for this form, there is also nothing to do here.
        if (count($captchaRules) === 0) {
            return;
        }

        // Captcha-protection only works, if a request-object was stated via the requestObject() method,
        // so we throw an exception, if this was not the case.
        if (is_null($form->requestObject)) {
            throw new MandatoryOptionMissingException(
                'The form with ID "' . $form->attributes->id . '" should display a captcha, ' .
                'but no request-object was stated via the Form::open()->requestObject() method. ' .
                'Captcha only works if this is the case.'
            );
        }

        // Set where the captcha-answer will be stored in the session.
        $sessionKeyForCaptchaData = 'formbuilder.captcha.' . $form->requestObject;

        // We unset any old captcha-answer (from the previous request) currently set in the session for this request-object.
        $oldFlashKeys = session()->get('flash.old');
        if (is_array($oldFlashKeys) && in_array($sessionKeyForCaptchaData, $oldFlashKeys)) {
            session()->forget($sessionKeyForCaptchaData);
        }

        // Get requestLimit and decayTime from the captchaRules.
        $requestLimit = self::getRequestLimitFromRuleParams($captchaRules['captcha']);
        $decayTime = self::getDecayTimeFromRuleParams($captchaRules['captcha']);

        // Now let's see, if the limit for this particular request has been reached.
        // We use the laravel-built in RateLimiter for that.
        // The Key of the RateLimiter is a hash of the RequestObject and the client-IP.
        $rateLimiterKey = sha1($form->requestObject . request()->ip());

        // If the limit has been reached, we must append a captcha-field.
        // (A requestLimit of 0 means, a captcha is always required.)
        if (($requestLimit === 0) || app(RateLimiter::class)->tooManyAttempts($rateLimiterKey, $requestLimit, $decayTime)) {

            // Establish captcha-data.
            $captchaData = self::establishCaptchaData($sessionKeyForCaptchaData);

            // Then we add the captcha-field to the output.
            $form->appendChild(
                (new TextInputElement())
                    ->name('_captcha')
                    ->required(true)
                    ->value('')
                    ->label($captchaData['question'])
                    ->placeholder(trans('Nicat-FormBuilder::formbuilder.captcha_placeholder'))
                    ->helpText(trans('Nicat-FormBuilder::formbuilder.captcha_help_text'))
            );

        }
    }

    public static function validate($attribute, $value, $parameters, $validator)
    {
        // If captcha-protection is not enabled in the config, we immediately return true.
        if (!config('formbuilder.captcha.enabled')) {
            return true;
        }

        // We need to get the name of the last resolved form-request-object from session.
        if (!session()->has('formbuilder.last_form_request_object')) {
            return false;
        }

        // Get requestLimit and decayTime from the rule-parameters.
        $requestLimit = self::getRequestLimitFromRuleParams($parameters);
        $decayTime = self::getDecayTimeFromRuleParams($parameters);

        // Now let's see, if the limit for this particular request has been reached.
        // We use the laravel-built-in RateLimiter for that.
        // The key of the RateLimiter is a hash of the RequestObject and the client-IP.
        // Therefore we try to get the name of the form-request-object,
        // which is the key for the RateLimiter.
        // (A requestLimit of 0 means, a captcha is always required.)
        $requestObject = session()->get('formbuilder.last_form_request_object');
        $rateLimiterKey = sha1($requestObject . request()->ip());
        if (($requestLimit === 0) || app(RateLimiter::class)->tooManyAttempts($rateLimiterKey, $requestLimit, $decayTime)) {

            // If no value was submitted for the _captcha field at all, we immediately return false.
            if (!request()->has('_captcha')) {
                return false;
            }

            // We get the required answer for this request from the session,
            // which was stored there by the FormBuilder.
            $requiredAnswer = session()->get('formbuilder.captcha.' . $requestObject . '.answer');

            // Check, if the submitted value is indeed the required answer.
            // If it is not, we return false.
            if (intval($value) !== $requiredAnswer) {
                return false;
            }

        }

        // We also count this hit.
        app(RateLimiter::class)->hit($rateLimiterKey, $decayTime);

        return true;

    }

    /**
     * Get request-limit to be used for captcha-protection.
     * Returns $parameters[0], if set.
     * Otherwise returns default value set in config.
     *
     * @param $parameters
     * @return int
     */
    public static function getRequestLimitFromRuleParams(array $parameters): int
    {
        if (isset($parameters[0]) && is_numeric($parameters[0])) {
            return (int)$parameters[0];
        }
        return (int)config('formbuilder.captcha.default_limit');
    }

    /**
     * Get decay-time to be used for captcha-protection.
     * Returns $parameters[1], if set.
     * Otherwise returns default value set in config.
     *
     * @param $parameters
     * @return int
     */
    public static function getDecayTimeFromRuleParams(array $parameters): int
    {
        if (isset($parameters[1]) && is_numeric($parameters[1])) {
            return (int)$parameters[1];
        }
        return (int)config('formbuilder.captcha.decay_time');
    }

    /**
     * Generates captcha-data (question and answer).
     *
     * @return array
     */
    public static function generateCaptchaData()
    {
        $num1 = rand(1, 10) * rand(1, 3);
        $num2 = rand(1, 10) * rand(1, 3);
        $answer = $num1 + $num2;
        $question = trans('Nicat-FormBuilder::formbuilder.captcha_questions.math', ['calc' => $num1 . ' + ' . $num2]);
        return [
            'question' => $question,
            'answer' => $answer,
        ];
    }

    /**
     * Makes sure, captchaData (question and answer) for this form are present in the session
     * and returns it.
     *
     * @param $sessionKeyForCaptchaData
     * @return array
     */
    public static function establishCaptchaData(string $sessionKeyForCaptchaData): array
    {
        // If the same request-object is used in multiple forms of a page,
        // there might already be captchaData in the session.
        // If this is the case, we return that.
        if (session()->has($sessionKeyForCaptchaData)) {
            return session()->get($sessionKeyForCaptchaData);
        }

        // Otherwise we generate a captcha-question and an answer.
        $captchaData = self::generateCaptchaData();

        // Furthermore we also set the required captcha-answer in the session.
        // This is used when the CaptchaValidator actually checks the captcha.
        session()->flash($sessionKeyForCaptchaData, $captchaData);

        return $captchaData;
    }


}