<?php

namespace Webflorist\FormFactory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Webflorist\FormFactory\Http\Requests\UploadFileRequest;
use Webflorist\FormFactory\Utilities\FileUploadManager;

class FormFactoryController extends Controller
{
    public function getCsrfToken(Request $request)
    {
        return response()->json(csrf_token());
    }

    public function uploadFile(UploadFileRequest $request)
    {
        $formId = $request->get('_formID');
        $response = [];
        foreach ($request->allFiles() as $fieldName => $file) {
            $response[$fieldName] = FileUploadManager::storeFile($file, $formId);
        }
        return response()->json($response);
    }
}
