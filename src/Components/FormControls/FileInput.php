<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Webflorist\FormFactory\Components\FormControls\Traits\FieldTrait;
use Webflorist\FormFactory\Components\FormControls\Contracts\FieldInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\FormControlTrait;
use Webflorist\FormFactory\Components\FormControls\Contracts\FormControlInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\HelpTextTrait;
use Webflorist\FormFactory\Components\FormControls\Traits\LabelTrait;
use Webflorist\FormFactory\Components\FormControls\Contracts\AutoTranslationInterface;
use Webflorist\FormFactory\Components\FormControls\Traits\AutoTranslationTrait;
use Webflorist\HtmlFactory\Components\FileInputComponent;

class FileInput
    extends FileInputComponent
    implements FormControlInterface, FieldInterface,   AutoTranslationInterface
{
    use FormControlTrait,
        FieldTrait,
        LabelTrait,
        HelpTextTrait,
        AutoTranslationTrait;

    /**
     * FileInput constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct();
        $this->name($name);
        $this->setupFormControl();
    }

    /**
     * Gets called after applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        parent::afterDecoration();
        $this->processFormControl();

        // If there is a current value,
        // we save save it under the 'currentFile' payload
        // and set the value to 'keep'.
        if ($this->attributes->isSet('value')) {
            $currentFile = $this->attributes->value;
            $this->attributes->value = 'keep';
            $this->payload($currentFile, 'currentFile');
        }
    }
}