<?php

namespace Webflorist\FormFactory\Components\Helpers;

use Webflorist\FormFactory\Components\FormControls\TextInput;
use Webflorist\HtmlFactory\Elements\DivElement;

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
        $this->appendContent(' ' . trans('webflorist-formfactory::formfactory.mandatory_fields'));
    }

}
