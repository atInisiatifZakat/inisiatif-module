<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\ProgramResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Inisiatif\Admin\Resources\ProgramResource;

final class CreateProgram extends CreateRecord
{
    protected static string $resource = ProgramResource::class;
}
