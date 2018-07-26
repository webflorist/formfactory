<?php

namespace Nicat\FormFactory\Decorators\Bootstrap\v4;

use Nicat\FormFactory\Decorators\Bootstrap\v3\StyleButtonGroup as Bootstrap3StyleButtonGroup;

class StyleButtonGroup extends Bootstrap3StyleButtonGroup
{

    /**
     * Returns an array of frontend-framework-ids, this decorator is specific for.
     *
     * @return string[]
     */
    public static function getSupportedFrameworks(): array
    {
        return [
            'bootstrap:4'
        ];
    }

}