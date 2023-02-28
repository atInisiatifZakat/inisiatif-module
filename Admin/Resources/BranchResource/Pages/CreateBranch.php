<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\BranchResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Inisiatif\Admin\Resources\BranchResource;

final class CreateBranch extends CreateRecord
{
    protected static string $resource = BranchResource::class;
}
