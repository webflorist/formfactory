<?php

namespace FormFactoryTests;

use Form;
use HtmlFactoryTests\Traits\AppliesAttributeSets;
use HtmlFactoryTests\Traits\AssertsHtml;
use Webflorist\FormFactory\FormFactoryFacade;
use Webflorist\FormFactory\FormFactoryServiceProvider;
use Webflorist\HtmlFactory\HtmlFactoryFacade;
use Webflorist\HtmlFactory\HtmlFactoryServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{

    use AssertsHtml, AppliesAttributeSets;

    protected $frontendFramework;

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
        $app['config']->set('htmlfactory.frontend_framework', $this->frontendFramework);
    }

    protected function setFrontendFramework(string $frameworkId,string $frameworkVersion=null) {
        $frontendFramework = $frameworkId;
        if (!is_null($frameworkVersion)) {
            $frontendFramework .= ':'.$frameworkVersion;
        }
        $this->frontendFramework = $frontendFramework;
        $this->refreshApplication();
    }

    /**
     * Setup the test environment.
     *
     * @return void
     * @throws \Webflorist\HtmlFactory\Exceptions\AttributeNotAllowedException
     * @throws \Webflorist\HtmlFactory\Exceptions\AttributeNotFoundException
     */
    protected function setUp()
    {
        parent::setUp();
        Form::open('myFormId');
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown()
    {
        Form::close();
    }


}