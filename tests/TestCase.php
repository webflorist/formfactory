<?php

namespace FormFactoryTests;

use Form;
use FormFactoryTests\Browser\Requests\VueFormTestRequest;
use HtmlFactoryTests\Traits\AppliesAttributeSets;
use HtmlFactoryTests\Traits\AssertsHtml;
use Webflorist\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Webflorist\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\HelpTextTrait;
use Webflorist\FormFactory\FormFactoryFacade;
use Webflorist\FormFactory\FormFactoryServiceProvider;
use Webflorist\HtmlFactory\HtmlFactoryFacade;
use Webflorist\HtmlFactory\HtmlFactoryServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{

    use AssertsHtml, AppliesAttributeSets, TestCaseTrait;

    protected $openForm = true;
    protected $openVueForm = false;
    protected $closeForm = true;

    protected function getPackageProviders($app)
    {
        return [
            HtmlFactoryServiceProvider::class,
            FormFactoryServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Html' => HtmlFactoryFacade::class,
            'Form' => FormFactoryFacade::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->setUpConfig($app);
    }

    /**
     * Setup the test environment.
     *
     * @return void
     * @throws \Webflorist\FormFactory\Exceptions\FormRequestClassNotFoundException
     */
    protected function setUp()
    {
        parent::setUp();
        if ($this->openForm) {

            if ($this->openVueForm) {
                Form::vOpen('myFormId');
            }
            else {
                Form::open('myFormId');
            }
        }
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown()
    {
        if ($this->closeForm) {
            Form::close();
        }
    }

    /**
     * Applies various attributes for a complex test.
     *
     * @param FormControlInterface $formControl
     */
    protected function applyComplexAttributes(FormControlInterface $formControl)
    {

        if ($formControl->isAField()) {
            /** @var FieldInterface $formControl */
            $formControl->errors(['myFirstError','mySecondError']);
            $formControl->rules('required|alpha|max:10');
        }

        if ($formControl->canHaveHelpText()) {
            /** @var HelpTextTrait $formControl */
            $formControl->helpText('myHelpText');
        }

    }


}