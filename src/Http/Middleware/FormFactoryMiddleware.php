<?php

namespace Webflorist\FormFactory\Http\Middleware;

use Closure;
use Webflorist\FormFactory\Utilities\FileUploadManager;

class FormFactoryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('_formID') && $request->has('form_factory_uploads')) {
            $requestData = $request->all();
            foreach ($request->get('form_factory_uploads') as $fieldName) {
                $fileId = $request->get($fieldName);
                $requestData[$fieldName] = FileUploadManager::retrieveFile(
                    $fileId,
                    $request->get('_formID')
                );
            }
            unset($requestData['form_factory_uploads']);

            $request->replace($requestData);
        }
        return $next($request);
    }
}