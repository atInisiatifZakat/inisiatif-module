<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Forms\Components;

use Filament\Forms\Components\TextInput;

final class InisiatifRefNumberInput
{
    public static function make(): TextInput
    {
        return TextInput::make('inisiatif_ref_id')
            ->label('Inisiatif Ref ID')
            ->required();
    }
}
