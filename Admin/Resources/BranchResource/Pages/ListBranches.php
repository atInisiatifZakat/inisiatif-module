<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\BranchResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Inisiatif\Admin\Resources\BranchResource;

final class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;
}
