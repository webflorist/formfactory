<?php

namespace Nicat\FormBuilder\Components\Additional;

use Nicat\FormBuilder\FormBuilder;
use Nicat\HtmlBuilder\Elements\SupElement;

class RequiredFieldIndicator extends SupElement
{

    /**
     * Gets called during construction.
     * Overwrite to perform setup-functionality.
     */
    protected function setUp()
    {
        $this->appendContent('*');
        app(FormBuilder::class)->requiredFieldIndicatorUsed = true;
    }

}