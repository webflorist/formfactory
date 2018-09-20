<?php

namespace Nicat\FormFactory\Decorators\Bootstrap\v4;

use Nicat\FormFactory\Decorators\Bootstrap\v3\StyleButtonGroup as Bootstrap3StyleButtonGroup;

class StyleButtonGroup extends Bootstrap3StyleButtonGroup
{

    /**
     * Returns the group-ID of this decorator.
     *
     * Returning null means this decorator will always be applied.
     *
     * @return string|null
     */
    public static function getGroupId()
    {
        return 'bootstrap:v4';
    }

}