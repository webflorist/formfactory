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
            File::createWithContent('test.txt', 'this is a test'),
            'myTestForm',
            'myTestField'
        );

        $uploadedFile = FileUploadManager::retrieveFile(
            $fileId,
            'myTestForm',
            'myTestField'
        );

        $this->assertEquals(
            'test.txt',
            $uploadedFile->getClientOriginalName()
        );

        $this->assertEquals(
            'this is a test',
            $uploadedFile->get()
        );

    }

}