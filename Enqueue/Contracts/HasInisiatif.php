<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Enqueue\Contracts;

interface HasInisiatif
{
    public function checkUsingReference(string $refId): bool;

    public function findUsingReference(string $refId);
}
