<?php

namespace Webflorist\FormFactory\Utilities;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileUploadManager
{

    public static function storeFile($file, string $formId, string $fieldName)
    {
        $fileId = Str::random();
        $key = self::getFileCacheKey($fileId, $formId, $fieldName);
        cache()->put($key, self::processFile($file), now()->addMinutes(30));
        return $fileId;
    }

    private static function processFile($file) {
        if (is_a($file, \Illuminate\Http\Testing\File::class)) {
            /** @var \Illuminate\Http\Testing\File $file */
            $file = new UploadedFile($file->getPathname(), $file->getClientOriginalName(), $file->getClientMimeType(), null, $test = true);
        }
        /** @var UploadedFile $file */
        return [
            'content' => $file->get(),
            'originalName' => $file->getClientOriginalName(),
            'mimeType' => $file->getClientMimeType()
        ];
    }

    public static function retrieveFile(string $fileId, string $formId, string $fieldName)
    {
        $key = self::getFileCacheKey($fileId, $formId, $fieldName);
        if (!cache()->has($key)) {
            throw new HttpResponseException(response("Uploaded File not found.", 408));
        }
        $storedFileData = cache()->get($key);

        cache()->forget($key);

        $tmpFile = tempnam(sys_get_temp_dir(), '/form_factory_');
        file_put_contents($tmpFile, $storedFileData['content']);

        return new UploadedFile($tmpFile, $storedFileData['originalName'], $storedFileData['mimeType'], null, true);
    }

    /**
     * @param string $formId
     * @param string $fieldName
     * @return string
     */
    private static function getFileCacheKey(string $fileId, string $formId, string $fieldName): string
    {
        $fieldName = FormFactoryTools::convertArrayFieldHtmlName2DotNotation($fieldName);
        $sessionId = session()->getId();
        return "formfactory.$sessionId.$formId.uploaded-files.$fieldName.$fileId";
    }

}