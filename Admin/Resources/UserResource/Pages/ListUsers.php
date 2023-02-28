<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\UserResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Inisiatif\Admin\Resources\UserResource;

final class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
}
