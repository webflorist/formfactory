<?php

namespace Webflorist\FormFactory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
        return response()->json([
            'file_upload_id' => FileUploadManager::storeFile(
                $request->file('file'),
                $request->get('_formID'),
                $request->get('fieldName')
            )
        ]);
    }
}
