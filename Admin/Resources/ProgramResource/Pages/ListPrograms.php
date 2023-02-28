<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\ProgramResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Inisiatif\Admin\Resources\ProgramResource;

final class ListPrograms extends ListRecords
{
    protected static string $resource = ProgramResource::class;
}
