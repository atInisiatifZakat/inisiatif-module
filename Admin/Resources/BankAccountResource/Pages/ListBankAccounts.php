<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\BankAccountResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Inisiatif\Admin\Resources\BankAccountResource;

final class ListBankAccounts extends ListRecords
{
    protected static string $resource = BankAccountResource::class;
}
