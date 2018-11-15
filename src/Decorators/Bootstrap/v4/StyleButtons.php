<?php

namespace Webflorist\FormFactory\Decorators\Bootstrap\v4;

use Webflorist\FormFactory\Decorators\Bootstrap\v3\StyleButtons as Bootstrap3StyleButtons;

class StyleButtons extends Bootstrap3StyleButtons
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