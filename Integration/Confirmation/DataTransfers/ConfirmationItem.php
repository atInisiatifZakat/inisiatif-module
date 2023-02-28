<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Integration\Confirmation\DataTransfers;

use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

final class ConfirmationItem extends DataTransferObject
{
    #[MapTo('product_type')]
    public string $type;

    public string $product;

    public int|float $amount;
}
