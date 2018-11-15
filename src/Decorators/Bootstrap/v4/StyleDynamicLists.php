<?php

namespace Webflorist\FormFactory\Decorators\Bootstrap\v4;

use Webflorist\FormFactory\Decorators\Bootstrap\v3\StyleDynamicLists as Bootstrap3StyleDynamicLists;

class StyleDynamicLists extends Bootstrap3StyleDynamicLists
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