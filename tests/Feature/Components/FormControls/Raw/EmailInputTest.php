<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Raw;

use FormFactoryTests\TestCase;

class EmailInputTest extends TestCase
{

    protected $viewBase = 'formfactory::raw';

    public function testSimple()
    {
        $element = \Form::email('email');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_email">Email</label>
                <input type="email" name="email" id="myFormId_email" placeholder="Email" />
            ',
            $element->generate()
        );
    }

    public function testComplex()
    {
        $element = \Form::email('email')
            ->helpText('myHelpText')
            ->errors(['myFirstError', 'mySecondError'])
            ->rules('required|alpha|max:10');

        $this->assertHtmlEquals(
            '
                <label for="myFormId_email">Email<sup>*</sup></label>
                <div id="myFormId_email_errors">
                    <div>myFirstError</div>
                    <div>mySecondError</div>
                </div>
                <input type="email" name="email" id="myFormId_email" required aria-describedby="myFormId_email_errors myFormId_email_helpText" aria-invalid="true" pattern="[a-zA-Z]+" maxlength="10" placeholder="Email" />
                <small id="myFormId_email_helpText">myHelpText</small>
            ',
            $element->generate()
        );
    }

}