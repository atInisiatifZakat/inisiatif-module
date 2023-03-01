<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Supports;

use hisorange\BrowserDetect\Parser;

final class Browser
{
    public static function browserName(): string
    {
        return self::parser()->detect()->browserName();
    }

    public static function platformName(): string
    {
        return self::parser()->detect()->platformName();
    }

    private static function parser(): Parser
    {
        return app('browser-detect');
    }
}
