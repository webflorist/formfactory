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

    protected $decorators = [];
    protected $openForm = true;
    protected $closeForm = true;
    protected $enableVue = false;

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
        $app['config']->set('htmlfactory.decorators', $this->decorators);
        $app['config']->set('formfactory.vue.enabled', $this->enableVue);
    }

    protected function setDecorators(array $decorators) {
        $this->decorators = $decorators;
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
        if ($this->openForm) {
            Form::open('myFormId');
        }
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     * @throws \Nicat\FormFactory\Exceptions\OpenElementNotFoundException
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