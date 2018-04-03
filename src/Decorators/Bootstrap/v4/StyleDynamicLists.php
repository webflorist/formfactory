<?php

namespace Nicat\FormFactory\Decorators\Bootstrap\v4;

use Nicat\FormFactory\Decorators\Bootstrap\v3\StyleDynamicLists as Boostrap3StyleDynamicLists;

class StyleDynamicLists extends Boostrap3StyleDynamicLists
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