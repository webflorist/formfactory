<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\FormFactory;
use Nicat\HtmlFactory\Elements\SupElement;

class RequiredFieldIndicator extends SupElement
{

    /**
     * Gets called during construction.
     * Overwrite to perform setup-functionality.
     */
    protected function setUp()
    {
        $this->appendContent('*');
        app(FormFactory::class)->requiredFieldIndicatorUsed = true;
    }

}