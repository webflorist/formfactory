<?php

namespace Nicat\FormBuilder\Components\Additional;

use Nicat\HtmlBuilder\Elements\DivElement;

class RequiredFieldsLegend extends DivElement
{

    /**
     * Gets called during construction.
     * Overwrite to perform setup-functionality.
     */
    protected function setUp()
    {
        $this->addClass('text-muted small');
        $this->appendChild(new RequiredFieldIndicator());
        $this->appendChild(' ' . trans('Nicat-FormBuilder::formbuilder.mandatory_fields'));
    }

}