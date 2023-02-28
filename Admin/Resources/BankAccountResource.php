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
use Ziswapp\Domain\Foundation\Model\BankAccount;

final class BankAccountResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'bank-account';

    protected static ?string $model = BankAccount::class;

    protected static ?string $navigationGroup = 'Rekening';

    protected static ?string $navigationIcon = 'heroicon-o-archive';

    protected static ?string $label = 'Rekening';

    protected static ?string $pluralLabel = 'Rekening';

    protected static ?string $navigationLabel = 'Rekening';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Grid::make()->schema([
                    Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required()
                        ->searchable(),
                    Select::make('bank_id')->label('Bank')->required()
                        ->relationship('bank', 'name', function (Builder $query, Component $livewire): Builder {
                            return $livewire instanceof BankAccountResource\Pages\EditBankAccount ? $query : $query->where('is_active', true);
                        }),
                    TextInput::make('number')->numeric()->label('Rekening')->required(),
                    TextInput::make('name')->label('Atas nama')->required(),
                ]),
                Toggle::make('is_active')->label('Rekening aktif')->default(true),
                Toggle::make('is_inisiatif_verified')->label('Transaksi harus di verifikasi oleh Inisiatif Zakat Indonesia')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('bank.name')
                ->label('Bank')
                ->sortable(),
            TextColumn::make('number')
                ->label('Rekening')
                ->searchable()
                ->sortable(),
            TextColumn::make('name')
                ->label('Atas nama')
                ->searchable()
                ->sortable(),
            IconColumn::make('is_active')
                ->boolean()
                ->label('Aktif'),
            IconColumn::make('is_inisiatif_verified')
                ->boolean()
                ->label('Verifikasi IZI'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => BankAccountResource\Pages\ListBankAccounts::route('/'),
            'create' => BankAccountResource\Pages\CreateBankAccount::route('/create'),
            'edit' => BankAccountResource\Pages\EditBankAccount::route('/{record}/edit'),
        ];
    }
}
