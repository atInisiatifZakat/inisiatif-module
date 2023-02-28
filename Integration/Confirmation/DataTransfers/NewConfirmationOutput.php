<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Integration\Confirmation\DataTransfers;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

final class NewConfirmationOutput extends DataTransferObject
{
    public string $id;

    #[MapFrom('identification_number')]
    public string $identificationNumber;
}
