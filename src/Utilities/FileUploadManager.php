<?php

namespace Webflorist\FormFactory\Utilities;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileUploadManager
{

    public static function storeFile($file, string $formId)
    {
        if (is_a($file, \Illuminate\Http\Testing\File::class)) {
            /** @var \Illuminate\Http\Testing\File $file */
            $file = new UploadedFile($file->getPathname(), $file->getClientOriginalName(), $file->getClientMimeType(), null, $test = true);
        }
        /** @var UploadedFile $file */
        $fileId = Str::random();
        $tmpFile = tempnam(sys_get_temp_dir(), '/form_factory_');
        rename($file->getPathname(), $tmpFile);
        $key = "webflorist-formfactory.forms.$formId.uploaded-files";
        $storedFiles = session()->has($key) ? session()->get($key) : [];
        $storedFiles[$fileId] = [
            'path' => $tmpFile,
            'originalName' => $file->getClientOriginalName(),
            'mimeType' => $file->getClientMimeType()
        ];
        session()->put($key, $storedFiles);
        return $fileId;
    }

    public static function retrieveFile(string $fileId, string $formId)
    {
        $key = "webflorist-formfactory.forms.$formId.uploaded-files.$fileId";
        if (!session()->has($key)) {
            throw new HttpResponseException(response("Uploaded File not found.", 408));
        }
        $storedFileData = session()->get($key);
        return new UploadedFile($storedFileData['path'], $storedFileData['originalName'], $storedFileData['mimeType']);
    }

}