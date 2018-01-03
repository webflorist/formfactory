<?php

namespace FormBuilderTests\Legacy\Forms\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaptchaTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            '_captcha' => 'captcha:0,1',
        ];

        return $rules;
    }
}
