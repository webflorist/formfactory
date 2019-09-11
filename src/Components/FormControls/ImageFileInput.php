<?php

namespace Webflorist\FormFactory\Components\FormControls;

use Intervention\Image\Image;

class ImageFileInput extends FileInput
{

    /**
     * Gets called after applying decorators.
     * Overwrite to perform manipulations.
     */
    protected function afterDecoration()
    {
        parent::afterDecoration();

        if ($this->attributes->isSet('value')) {
            if (is_object($this->attributes->value) && is_a($this->attributes->value, Image::class)) {
                $this->value((string)$this->attributes->value->encode('data-url'));
            }
        }
    }
}
