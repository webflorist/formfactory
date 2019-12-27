<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\HtmlFactory\Attributes\Manager\AttributeManager;
use Webflorist\HtmlFactory\Payload\Abstracts\Payload;

/**
 * Class TempField
 *
 * A TextInput component, that does not get registered with FormFactory.
 * (To be used as a template or for other temporary reasons.)
 *
 * @package Webflorist\FormFactory\Components\FormControls
 */
class TempField extends TextInput {

    /**
     * TempField constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {

        $this->attributes = new AttributeManager($this);
        $this->payload = new Payload();
        $this->setUp();
        $this->name($name);
    }



}
