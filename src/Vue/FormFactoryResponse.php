<?php

namespace Nicat\FormFactory\Vue;

use Illuminate\Http\JsonResponse;
use Nicat\FormFactory\Components\Form\AntiBotProtection\CaptchaProtection;
use Nicat\FormFactory\Components\Form\FieldRules\FieldRuleManager;
use Nicat\FormFactory\Utilities\FormFactoryTools;

/**
 * Use this Trait in your controller to create proper responses
 * for vue-enabled forms.
 *
 * Class FormFactoryResponse
 * @package Nicat\FormFactory
 */
class FormFactoryResponse
{

    public static function success($message=null, $restForm=true) {
        return new JsonResponse(
            [
                'message' => $message ?? trans('formfactory.default_success_message'),
                'captcha_question' => self::getCaptchaQuestion(),
                'reset_form' => $restForm
            ],
            200
        );
    }

    public static function error(array $errors)
    {
        return new JsonResponse(
            [
                'errors' => $errors,
                'captcha_question' => self::getCaptchaQuestion()
            ],
            422
        );
    }

    private static function getCaptchaQuestion()
    {

        $captchaQuestion = null;
        if (session()->has('formfactory.last_form_request_object')) {
            $formRequestClass = session()->get('formfactory.last_form_request_object');
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
        }

        return $captchaQuestion;
    }

}