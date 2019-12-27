<?php

namespace FormFactoryTests\Unit;

use FormFactoryTests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Webflorist\FormFactory\Http\Middleware\FormFactoryMiddleware;

class ApiRoutesTest extends TestCase
{
    protected $vueEnabled = true;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['router']->middleware('web')->middleware(FormFactoryMiddleware::class)
            ->post('test_uploaded_file', function (Request $request) {
                /** @var UploadedFile $uploadedTestFile */
                $uploadedTestFile = request()->get('myFieldName');
                return response()->json([
                    'name' => $uploadedTestFile->getClientOriginalName()
                ]);
            });
    }


    public function test_get_csrf_token()
    {
        $tokenRequest = $this->get('/api/form-factory/csrf-token');
        $this->assertEquals(
            json_encode(csrf_token()),
            $tokenRequest->getContent()
        );
    }

    public function test_upload_file()
    {

        $response = $this->json('POST', '/api/form-factory/file-upload', [
            'file' => UploadedFile::fake()->image('my-test-file.pdf'),
            'fieldName' => 'myFieldName',
            '_formID' => 'myFormId'
        ]);

        $responseContent = json_decode($response->getContent(), true);

        $this->assertArrayHasKey(
            'file_upload_id',
            $responseContent
        );

        $uploadedFileKey = $responseContent['file_upload_id'];

        $response = $this->post('/test_uploaded_file', [
            'form_factory_uploads' => [
                'myFieldName'
            ],
            'myFieldName' => [
                'file_upload_id' => $uploadedFileKey
            ],
            '_formID' => 'myFormId'
        ]);
        $this->assertEquals(
            json_encode(['name' => 'my-test-file.pdf']),
            $response->getContent()
        );
    }

}