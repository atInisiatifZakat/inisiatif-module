<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\FundingTypeResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Inisiatif\Admin\Resources\FundingTypeResource;

final class ListFundingTypes extends ListRecords
{
    protected static string $resource = FundingTypeResource::class;
}
