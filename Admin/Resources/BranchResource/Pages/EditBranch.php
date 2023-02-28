<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\BranchResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Inisiatif\Admin\Resources\BranchResource;

final class EditBranch extends EditRecord
{
    protected static string $resource = BranchResource::class;
}
