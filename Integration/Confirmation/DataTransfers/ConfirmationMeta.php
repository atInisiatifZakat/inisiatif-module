<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Integration\Confirmation\DataTransfers;

use Spatie\DataTransferObject\DataTransferObject;

final class ConfirmationMeta extends DataTransferObject
{
    public string $key;

    public string $name;

    public string $value;
}
