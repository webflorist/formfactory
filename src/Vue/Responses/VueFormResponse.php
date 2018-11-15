<?php

namespace Webflorist\FormFactory\Vue\Responses;

use Illuminate\Http\JsonResponse;
use Webflorist\FormFactory\Components\Form\AntiBotProtection\CaptchaProtection;
use Webflorist\FormFactory\Components\Form\FieldRules\FieldRuleManager;
use Webflorist\FormFactory\Exceptions\FormRequestClassNotFoundException;
use Webflorist\FormFactory\Utilities\FormFactoryTools;

/**
 * Abstract class for the JsonResponse of a VueForm.
 *
 * Class VueFormResponse
 * @package Webflorist\FormFactory
 */
abstract class VueFormResponse extends JsonResponse
{

    protected $vueFormResponseData = [];

    /**
     * Tries to retrieve the current Captcha-question for the form.
     *
     * @return null|string
     */
    protected function getCaptchaQuestion()
    {
        $captchaQuestion = null;
        if (session()->has('formfactory.last_form_request_object')) {
            $formRequestClass = session()->get('formfactory.last_form_request_object');
            try {
                $formRequest = FormFactoryTools::initFormRequestObject($formRequestClass);

                if (isset($formRequest->rules()['_captcha'])) {
                    $parsedRules = FieldRuleManager::parseRules($formRequest->rules()['_captcha']);
                    if (isset($parsedRules['captcha'])) {
                        $captchaProtection = (new CaptchaProtection(
                            $formRequestClass,
                            $parsedRules['captcha']
                        ));
                        $captchaProtection->setUp();
                        $captchaQuestion = $captchaProtection->getQuestion();
                    }
                }
            } catch (FormRequestClassNotFoundException $e) {
            }
        }

        return $captchaQuestion;
    }


    /**
     * Overwritten to manipulate the response-data.
     *
     * @return JsonResponse
     */
    public function sendContent()
    {
        $this->vueFormResponseData['captcha_question'] = $this->getCaptchaQuestion();
        $this->setData($this->vueFormResponseData);
        $this->update();
        return parent::sendContent();
    }


}