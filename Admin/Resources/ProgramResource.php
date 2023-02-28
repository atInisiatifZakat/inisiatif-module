<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources;

use Livewire\Component;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Ziswapp\Domain\Transaction\Model\Program;
use Modules\Inisiatif\Admin\Forms\Components\InisiatifRefNumberInput;

final class ProgramResource extends Resource
{
    protected static ?string $slug = 'program';

    protected static ?string $model = Program::class;

    protected static ?string $navigationGroup = 'Program';

    protected static ?string $navigationIcon = 'heroicon-o-archive';

    protected static ?string $label = 'Program';

    protected static ?string $pluralLabel = 'Program';

    protected static ?string $navigationLabel = 'Program';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                TextInput::make('name')->label('Program')->required(),
                Grid::make()->schema([
                    Select::make('program_category_id')->label('Katgori')->required()
                        ->relationship('category', 'name', static function (Builder $query, Component $livewire): Builder {
                            return $livewire instanceof ProgramResource\Pages\EditProgram ? $query : $query->where('is_active', true);
                        }),
                    Select::make('branch_id')->label('Cabang')->required()
                        ->relationship('branch', 'name', static function (Builder $query, Component $livewire): Builder {
                            return $livewire instanceof ProgramResource\Pages\EditProgram ? $query : $query->where('is_active', true);
                        }),
                    InisiatifRefNumberInput::make(),
                ]),
                Toggle::make('is_active')->label('Porgram aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('branch.name')
                ->label('Cabang')
                ->sortable(),
            TextColumn::make('category.name')
                ->label('Kategori')
                ->sortable(),
            TextColumn::make('name')
                ->label('Program')
                ->searchable()
                ->sortable(),
            IconColumn::make('is_active')
                ->label('Aktif')
                ->boolean(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ProgramResource\Pages\ListPrograms::route('/'),
            'create' => ProgramResource\Pages\CreateProgram::route('/create'),
            'edit' => ProgramResource\Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
