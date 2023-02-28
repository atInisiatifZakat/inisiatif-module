<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\BankAccountResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Inisiatif\Admin\Resources\BankAccountResource;

final class CreateBankAccount extends CreateRecord
{
    protected static string $resource = BankAccountResource::class;
}
