<?php

namespace Webflorist\FormFactory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function rules()
    {
        return [
            '_formID' => ['required']
        ];
    }
}