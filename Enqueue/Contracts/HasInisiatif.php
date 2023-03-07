<?php

namespace Modules\Inisiatif\Enqueue\Contracts;

interface HasInisiatif
{
    public function checkUsingReference(string $refId): bool;

    public function findUsingReference(string $refId);
}
