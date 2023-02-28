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
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\BooleanColumn;
use Ziswapp\Domain\Foundation\Model\FundingType;
use Modules\Inisiatif\Admin\Forms\Components\InisiatifRefNumberInput;

final class FundingTypeResource extends Resource
{
    protected static ?string $slug = 'funding-type';

    protected static ?string $model = FundingType::class;

    protected static ?string $navigationGroup = 'Jenis dana';

    protected static ?string $navigationIcon = 'heroicon-o-archive';

    protected static ?string $label = 'Jenis dana';

    protected static ?string $pluralLabel = 'Jenis dana';

    protected static ?string $navigationLabel = 'Jenis dana';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                TextInput::make('name')->label('Jenis dana')->required(),
                Grid::make()->schema([
                    Select::make('funding_category_id')->label('Kategori')->required()
                        ->relationship('category', 'name', function (Builder $query, Component $livewire): Builder {
                            return $livewire instanceof FundingTypeResource\Pages\EditFundingType ? $query : $query->where('is_active', true);
                        }),
                    InisiatifRefNumberInput::make(),
                ]),
                Toggle::make('is_active')->label('Jenis dana aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('category.name')
                ->label('Kategori')
                ->sortable(),
            TextColumn::make('name')
                ->label('Nama')
                ->searchable()
                ->sortable(),
            BooleanColumn::make('is_active')
                ->label('Aktif'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => FundingTypeResource\Pages\ListFundingTypes::route('/'),
            'create' => FundingTypeResource\Pages\CreateFundingType::route('/create'),
            'edit' => FundingTypeResource\Pages\EditFundingType::route('/{record}/edit'),
        ];
    }
}
