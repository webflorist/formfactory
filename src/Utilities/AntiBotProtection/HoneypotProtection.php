<?php

namespace Nicat\FormFactory\Utilities\AntiBotProtection;

use Nicat\FormFactory\Components\Helpers\FieldWrapper;
use Nicat\FormFactory\Components\FormControls\TextInput;
use Nicat\FormFactory\FormFactory;
use Nicat\FormFactory\Utilities\Forms\FormInstance;

class HoneypotProtection
{

    /**
     * Append the honeypot-field to the Form, if honeypot-protection is enabled in the config.
     *
     * @param FormInstance $form
     */
    public static function setUp(FormInstance $form)
    {
        if (config('formfactory.honeypot.enabled')) {

            // We retrieve the honeypot-rules.
            $honeypotRules = $form->rules->getRulesForField('_honeypot');

            // If there are any, ...
            if (count($honeypotRules) > 0) {

                // ...we add the honeypot-field wrapped in a hidden wrapper.
                $honeypotField = FormFactory::singleton()->text(self::getHoneypotFieldName())
                    ->value("")
                    ->label(trans('Nicat-FormFactory::formfactory.honeypot_field_label'));

                $honeypotField->wrap(
                    (new FieldWrapper($honeypotField))->hidden()
                );
                $form->getFormElement()->appendContent(
                    $honeypotField
                );
            }
        }
    }

    /**
     * The registered validator for the _honeypot field.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public static function validate($attribute, $value, $parameters, $validator) {

        $isValid = true;

        // We only validate, if honeypot-protection is basically enabled in the config.
        if (config('formfactory.honeypot.enabled')) {

            $honeypotFieldName = self::getHoneypotFieldName();

            $fullRequest = request()->all();

            if (array_key_exists($honeypotFieldName,$fullRequest)) {
                if (strlen($fullRequest[$honeypotFieldName])>0) {
                    $isValid = false;
                }
            }
            else {
                $isValid = false;
            }

        }

        return $isValid;

    }

    /**
     * Returns the string to be used as the honeypot-field-name.
     *
     * @return string
     */
    private static function getHoneypotFieldName()
    {
        return md5(csrf_token());
    }


}