<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\ProgramResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Inisiatif\Admin\Resources\ProgramResource;

final class EditProgram extends EditRecord
{
    protected static string $resource = ProgramResource::class;
}
