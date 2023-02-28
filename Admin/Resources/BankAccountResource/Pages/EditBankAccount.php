<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\BankAccountResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Inisiatif\Admin\Resources\BankAccountResource;

final class EditBankAccount extends EditRecord
{
    protected static string $resource = BankAccountResource::class;
}
