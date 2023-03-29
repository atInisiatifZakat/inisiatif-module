<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources;

use Closure;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Ziswapp\Admin\Forms\AddressInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Ziswapp\Domain\Foundation\Model\Branch;
use Modules\Inisiatif\Admin\Forms\Components\InisiatifRefNumberInput;

final class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $slug = 'branches';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static ?string $label = 'Kantor pewakilan';

    protected static ?string $pluralLabel = 'Kantor pewakilan';

    protected static ?string $navigationLabel = 'Kantor pewakilan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Grid::make()->schema([
                    Select::make('type')
                        ->label('Tipe')
                        ->required()
                        ->reactive()
                        ->options([
                            'KC' => 'Kantor Cabang',
                            'KCP' => 'Kantor Cabang Pembantu',
                        ])
                        ->default('KC')
                        ->disablePlaceholderSelection(),
                    Select::make('parent_id')
                        ->label('Kantor Cabang')
                        ->required()
                        ->relationship('parent', 'name', function (Builder $query, Closure $get): Builder {
                            return $get('type') === 'KC' ?
                                $query->whereNull('parent_id') :
                                $query->whereNotNull('parent_id')->where('type', 'KC');
                        }),
                ]),
                TextInput::make('name')
                    ->required()
                    ->autocomplete('off')
                    ->label('Nama')
                    ->placeholder('Nama cabang disini'),
                AddressInput::make(),
                Grid::make()->schema([
                    InisiatifRefNumberInput::make()
                        ->nullable()
                        ->hidden(! \config('inisiatif.mitra_ramadhan'))
                        ->helperText('Khusus untuk mitra ramadhan, ini wajib diisi dengan partner id'),
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->nullable()
                        ->hidden(! \config('inisiatif.mitra_ramadhan')),
                ]),
                Toggle::make('is_active')
                    ->label('Aktifkan cabang ini')
                    ->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('type')
                ->label('Tipe'),
            TextColumn::make('name')
                ->label('Nama')
                ->searchable()
                ->sortable(),
            TextColumn::make('address')
                ->label('Alamat')
                ->default('-'),
            IconColumn::make('is_active')
                ->label('Aktif')
                ->boolean(),
        ])->filters([
            SelectFilter::make('type')
                ->label('Tipe cabang')
                ->options([
                    'KC' => 'Kantor Cabang',
                    'KCP' => 'Kantor Cabang Pembantu',
                ]),
            Filter::make('active')
                ->label('Pilih cabang aktif')
                ->query(fn (Builder $query) => $query->orWhere('is_active', true)),
            Filter::make('non_active')
                ->label('Pilih cabang tidak aktif')
                ->query(fn (Builder $query) => $query->orWhere('is_active', false)),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => BranchResource\Pages\ListBranches::route('/'),
            'create' => BranchResource\Pages\CreateBranch::route('/create'),
            'edit' => BranchResource\Pages\EditBranch::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNotNull('parent_id');
    }
}
