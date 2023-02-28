<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Integration\Confirmation;

final class Credentials
{
    public function __construct(
        public readonly string $token,
        public readonly bool $isProduction = false
    ) {
    }
}
