<?php

namespace Webflorist\FormFactory\Components\Form\AntiBotProtection;

use Illuminate\Cache\RateLimiter;
use Webflorist\FormFactory\Components\Form\Form;
use Webflorist\FormFactory\Components\FormControls\TextInput;
use Webflorist\FormFactory\Exceptions\MandatoryOptionMissingException;
use Webflorist\FormFactory\FormFactory;

class CaptchaProtection
{
    /**
     *  FQCN of Form Request object
     *
     * @var string
     */
    private $formRequestClass;

    /**
     * Session key where to find the captcha-question and answer.
     *
     * @var string
     */
    private $sessionKey;

    /**
     * The configured request-limit.
     *
     * @var int
     */
    private $requestLimit;

    /**
     * The configured decay time.
     *
     * @var int
     */
    private $decayTime;

    /**
     * Key to use for Laravel's built in rate limier.
     *
     * @var string
     */
    private $rateLimiterKey;

    /**
     * The captcha question.
     *
     * @var string
     */
    private $question;

    /**
     * The captcha answer.
     *
     * @var string
     */
    private $answer;

    /**
     * CaptchaProtection constructor.
     *
     * @param string $formRequestClass
     * @param array $captchaRules
     */
    public function __construct(string $formRequestClass, array $captchaRules)
    {

        // If captcha-protection is not enabled in the config, or the captcha-rules are empty, there is nothing to do here.
        if (config('formfactory.captcha.enabled')) {

            $this->formRequestClass = $formRequestClass;
            $this->requestLimit = $this->getRequestLimitFromCaptchaRules($captchaRules);
            $this->decayTime = $this->getDecayTimeFromCaptchaRules($captchaRules);
            $this->setSessionKey();
            $this->setRateLimiterKey();
        }

    }

    /**
     * Sets up captcha-validation.
     */
    public function setUp()
    {

        // If the limit has been reached, we must append a captcha-field.
        if ($this->isRequestLimitReached()) {

            // Establish captcha-data.
            $this->establishCaptchaData();

        }
    }

    /**
     * Get request-limit to be used for captcha-protection from the stated validation-rules-array.
     * Returns $captchaRules[0], if set.
     * Otherwise returns default value set in config.
     *
     * @param $captchaRules
     * @return int
     */
    private function getRequestLimitFromCaptchaRules(array $captchaRules): int
    {
        if (isset($captchaRules[0]) && is_numeric($captchaRules[0])) {
            return (int)$captchaRules[0];
        }
        return (int)config('formfactory.captcha.default_limit');
    }

    /**
     * Get decay-time to be used for captcha-protection from the stated validation-rules-array.
     * Returns $captchaRules[1], if set.
     * Otherwise returns default value set in config.
     *
     * @param $captchaRules
     * @return int
     */
    private function getDecayTimeFromCaptchaRules(array $captchaRules): int
    {
        if (isset($captchaRules[1]) && is_numeric($captchaRules[1])) {
            return (int)$captchaRules[1];
        }
        return (int)config('formfactory.captcha.decay_time');
    }

    /**
     * Generates captcha-data (question and answer) and saves it in session as well as this class.
     */
    private function generateCaptchaData()
    {
        $num1 = rand(1, 10) * rand(1, 3);
        $num2 = rand(1, 10) * rand(1, 3);
        $this->answer = $num1 + $num2;
        $this->question = trans('Webflorist-FormFactory::formfactory.captcha_questions.math', ['calc' => $num1 . ' + ' . $num2]);

        session()->put(
            $this->sessionKey,
            [
                'question' => $this->question,
                'answer' => $this->answer
            ]
        );
    }



    /**
     * Retrieves Captcha Data from session.
     */
    private function getCaptchaDataFromSession()
    {
        if (session()->has($this->sessionKey)) {
            $sessionData = session()->get($this->sessionKey);
            $this->question = $sessionData['question'];
            $this->answer = $sessionData['answer'];
        }
    }

    /**
     * Makes sure, captchaData (question and answer) for this form are present in the session and in this object.
     */
    private function establishCaptchaData()
    {
        // If the same request-object is used in multiple forms of a page,
        // there might already be captchaData in the session.
        // If this is the case, we use that.
        // Otherwise we generate a new captcha-question and an answer.

        if (session()->has($this->sessionKey)) {
            $sessionData = session()->get($this->sessionKey);
            $this->question = $sessionData['question'];
            $this->answer = $sessionData['answer'];
        } else {
            $this->generateCaptchaData();
        }
    }

    /**
     * Sets the session key, where captcha-data should be saved.
     */
    private function setSessionKey()
    {
        $this->sessionKey = 'formfactory.captcha.' . $this->formRequestClass;
    }

    /**
     * Forgets any old captcha-answer (from the previous request) currently set in the session for this request-object.
     */
    protected function forgetOldSessionData()
    {
        if (session()->has($this->sessionKey)) {
            session()->forget($this->sessionKey);
        }
    }

    /**
     * Sets the key of the RateLimiter, which is a hash of the formRequestClass and the client-IP.
     */
    private function setRateLimiterKey()
    {
        $this->rateLimiterKey = sha1($this->formRequestClass . request()->ip());
    }

    /**
     * Has the request-limit for this captcha been reached?
     *
     * @return bool
     */
    public function isRequestLimitReached(): bool
    {
        if ($this->requestLimit === 0) {
            return true;
        }
        return app(RateLimiter::class)->tooManyAttempts($this->rateLimiterKey, $this->requestLimit, $this->decayTime);
    }

    /**
     * Performs captcha validation.
     *
     * @param string $answer
     * @return bool
     */
    public function validate($answer)
    {
        $this->getCaptchaDataFromSession();

        if ($this->isRequestLimitReached() && (strval($answer) !== strval($this->answer))) {
            $this->generateCaptchaData();
            return false;
        }

        $this->generateCaptchaData();
        return true;

    }

    /**
     * Hits the rate limiter.
     */
    public function hit()
    {
        app(RateLimiter::class)->hit($this->rateLimiterKey, $this->decayTime);
    }

    /**
     * Returns the captcha-question.
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

}