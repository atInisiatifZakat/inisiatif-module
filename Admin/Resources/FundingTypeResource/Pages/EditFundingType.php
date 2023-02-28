<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\FundingTypeResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Inisiatif\Admin\Resources\FundingTypeResource;

final class EditFundingType extends EditRecord
{
    protected static string $resource = FundingTypeResource::class;
}
