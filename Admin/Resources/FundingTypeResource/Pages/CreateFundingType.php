<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\FundingTypeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Inisiatif\Admin\Resources\FundingTypeResource;

final class CreateFundingType extends CreateRecord
{
    protected static string $resource = FundingTypeResource::class;
}
