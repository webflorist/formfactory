<?php

namespace Nicat\FormFactory\Components\FormControls;

use Nicat\FormFactory\Components\Traits\FieldTrait;
use Nicat\FormFactory\Components\Contracts\FieldInterface;
use Nicat\FormFactory\Components\Traits\FormControlTrait;
use Nicat\FormFactory\Components\Contracts\FormControlInterface;
use Nicat\FormFactory\Components\Traits\HelpTextTrait;
use Nicat\FormFactory\Components\Contracts\HelpTextInterface;
use Nicat\FormFactory\Components\Traits\LabelTrait;
use Nicat\FormFactory\Components\Contracts\LabelInterface;
use Nicat\FormFactory\Components\Contracts\AutoTranslationInterface;
use Nicat\FormFactory\Components\Traits\AutoTranslationTrait;
use Nicat\HtmlFactory\Components\SearchInputComponent;

class SearchInput
    extends SearchInputComponent
    implements FormControlInterface, FieldInterface, LabelInterface, HelpTextInterface, AutoTranslationInterface
{
    use FormControlTrait,
        FieldTrait,
        LabelTrait,
        HelpTextTrait,
        AutoTranslationTrait;

    /**
     * SearchInput constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct();
        $this->name($name);
        $this->setupFormControl();
    }

}