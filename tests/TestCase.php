<?php

namespace FormFactoryTests;

use Form;
use HtmlFactoryTests\Traits\AppliesAttributeSets;
use HtmlFactoryTests\Traits\AssertsHtml;
use Nicat\FormFactory\Components\Traits\CanHaveErrors;
use Nicat\FormFactory\Components\Traits\CanHaveRules;
use Nicat\FormFactory\FormFactoryFacade;
use Nicat\FormFactory\FormFactoryServiceProvider;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\ButtonElement;
use Nicat\HtmlFactory\Elements\InputElement;
use Nicat\HtmlFactory\HtmlFactoryFacade;
use Nicat\HtmlFactory\HtmlFactoryServiceProvider;
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
        $this->setUp();
    }

    /**
     * Setup the test environment.
     *
     * @return void
     * @throws \Nicat\HtmlFactory\Exceptions\AttributeNotAllowedException
     * @throws \Nicat\HtmlFactory\Exceptions\AttributeNotFoundException
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

    /**
     * Applies various attributes for a complex test.
     *
     * @param Element $element
     */
    protected function applyComplexAttributes(Element $element)
    {
        $this->applyGeneralAttributes($element);

        if ($element->is(InputElement::class)) {
            $this->applyInputAttributes($element);
        }

        if ($element->is(ButtonElement::class)) {
            $this->applyButtonAttributes($element);
        }

        if (method_exists($element,'errors')) {
            /** @var CanHaveErrors $element */
            $element->errors(['myFirstError','mySecondError']);
        }

        if (method_exists($element,'rules')) {
            /** @var CanHaveRules $element */
            $element->rules('required|alpha|max:10');
        }

    }


}