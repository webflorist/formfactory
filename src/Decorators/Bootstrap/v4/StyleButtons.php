<?php

namespace Nicat\FormFactory\Decorators\Bootstrap\v4;

use Nicat\FormFactory\Decorators\Bootstrap\v3\StyleButtons as Boostrap3StyleButtons;

class StyleButtons extends Boostrap3StyleButtons
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