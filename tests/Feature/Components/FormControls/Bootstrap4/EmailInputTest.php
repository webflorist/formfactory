<?php

namespace FormFactoryTests\Feature\Components\FormControls\Fields\Bootstrap4;

use FormFactoryTests\TestCase;

class EmailInputTest extends TestCase
{

    protected $viewBase = 'formfactory::bootstrap4';
    protected $decorators = ['bootstrap:v4'];

    public function testSimple()
    {
        $element = \Form::email('email');

        $this->assertHtmlEquals(
            '
                <div class="form-group">
                    <label for="myFormId_email">Email</label>
                    <input type="email" name="email" id="myFormId_email" class="form-control" placeholder="Email" />
                </div>
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
                <div class="form-group has-error">
                    <label for="myFormId_email">Email<sup>*</sup></label>
                    <div id="myFormId_email_errors">
                        <div>myFirstError</div>
                        <div>mySecondError</div>
                    </div>
                    <input type="email" name="email" id="myFormId_email" class="form-control" required pattern="[a-zA-Z]+" maxlength="10" placeholder="Email" aria-describedby="myFormId_email_errors myFormId_email_helpText" aria-invalid="true" />
                    <small id="myFormId_email_helpText" class="form-text text-muted">myHelpText</small>
                </div>
            ',
            $element->generate()
        );
    }

}