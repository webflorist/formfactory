<?php

namespace FormFactoryTests\Browser\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webflorist\FormFactory\Vue\FormFactoryFormRequestTrait;

class TimeLimitTestRequest extends FormRequest
{
    use FormFactoryFormRequestTrait;

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
        return [
            '_timeLimit' => 'timeLimit'
        ];
    }
}
