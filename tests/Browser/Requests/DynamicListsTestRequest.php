<?php

namespace FormFactoryTests\Browser\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DynamicListsTestRequest extends FormRequest
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
        return [
            'outer_dynamic_list' => 'array|min:2|max:3',
            'outer_dynamic_list.*.text' => 'required|min:2',
            'outer_dynamic_list.*.inner_dynamic_list' => 'array|min:1|max:2',
            'outer_dynamic_list.*.inner_dynamic_list.*' => 'alpha',
            ];
    }
}
