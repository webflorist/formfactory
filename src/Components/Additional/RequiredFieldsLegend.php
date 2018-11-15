<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\HtmlFactory\Elements\DivElement;

class RequiredFieldsLegend extends DivElement
{

    /**
     * Gets called during construction.
     * Overwrite to perform setup-functionality.
     */
    protected function setUp()
    {
        $this->addClass('text-muted small');
        $this->appendContent(new RequiredFieldIndicator());
        $this->appendContent(' ' . trans('Nicat-FormFactory::formfactory.mandatory_fields'));
    }

}