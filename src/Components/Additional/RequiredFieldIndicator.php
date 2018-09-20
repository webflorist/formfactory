<?php

namespace Nicat\FormFactory\Components\Additional;

use Nicat\FormFactory\FormFactory;
use Nicat\HtmlFactory\Elements\Abstracts\Element;
use Nicat\HtmlFactory\Elements\SupElement;

class RequiredFieldIndicator extends SupElement
{

    /**
     * The field this RequiredFieldIndicator belongs to.
     *
     * @var Element
     */
    public $field;

    /**
     * RequiredFieldIndicator constructor.
     *
     * @param Element|null $field
     */
    public function __construct($field = null)
    {
        parent::__construct();
        $this->field = $field;
    }


    /**
     * Gets called during construction.
     * Overwrite to perform setup-functionality.
     */
    protected function setUp()
    {
        $this->appendContent('*');
        FormFactory::singleton()->getOpenForm()->setRequiredFieldIndicatorUsed();
    }

}