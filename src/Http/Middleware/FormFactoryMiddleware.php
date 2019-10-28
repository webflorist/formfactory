<?php

namespace Webflorist\FormFactory\Http\Middleware;

use Closure;
use Webflorist\FormFactory\Utilities\FileUploadManager;
use Webflorist\FormFactory\Utilities\FormFactoryTools;

class FormFactoryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('_formID') && $request->has('form_factory_uploads')) {
            $requestData = $request->all();
            foreach ($request->get('form_factory_uploads') as $fieldName) {
                $fieldName = FormFactoryTools::convertArrayFieldHtmlName2DotNotation($fieldName);
                if ($request->has($fieldName) && is_array($fieldValue = $request->get($fieldName))) {

                    // Handle non-array field.
                    if (isset($fieldValue['file_upload_id'])) {
                        $requestData[$fieldName] = FileUploadManager::retrieveFile(
                            $fieldValue['file_upload_id'],
                            $request->get('_formID'),
                            $fieldName
                        );
                    } // Handle array field.
                    else {
                        foreach ($fieldValue as $fileKey => $fileData) {
                            if (isset($fileData['file_upload_id'])) {
                                $requestData[$fieldName][$fileKey] = FileUploadManager::retrieveFile(
                                    $fileData['file_upload_id'],
                                    $request->get('_formID'),
                                    $fieldName
                                );
                            }
                        }
                    }

                }
            }
            unset($requestData['form_factory_uploads']);
            $request->replace($requestData);
        }
        return $next($request);
    }
}