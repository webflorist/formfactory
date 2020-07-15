<?php

namespace FormFactoryTests\Unit;

use FormFactoryTests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Webflorist\FormFactory\Http\Middleware\FormFactoryMiddleware;
use Webflorist\FormFactory\Utilities\FileUploadManager;

class FileUploadManagerTest extends TestCase
{

    public function test_file_upload_manager()
    {
        $fileId = FileUploadManager::storeFile(
            new UploadedFile(__DIR__.'/testFile.txt', 'testFile.txt', null, null, true),
            'myTestForm',
            'myTestField'
        );

        $uploadedFile = FileUploadManager::retrieveFile(
            $fileId,
            'myTestForm',
            'myTestField'
        );

        $this->assertEquals(
            'testFile.txt',
            $uploadedFile->getClientOriginalName()
        );

        $this->assertEquals(
            'this is a test',
            $uploadedFile->get()
        );

    }

}