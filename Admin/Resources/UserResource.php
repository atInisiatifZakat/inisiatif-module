<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources;

use Closure;
use DateTimeZone;
use Livewire\Component;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Ziswapp\Domain\Foundation\Model\User;
use Filament\Forms\Components\CheckboxList;
use Ziswapp\Domain\Foundation\Enum\UserTeam;
use Ziswapp\Domain\Foundation\Enum\UserPosition;

final class UserResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Keamanan';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Select::make('branch_id')->required()->searchable()->reactive()
                    ->relationship('branch', 'name'),
                Grid::make()->schema([
                    TextInput::make('name')->label('Name')->required(),
                    TextInput::make('email')->label('Email')->required()
                        ->unique('users', 'email', fn (?User $record) => $record),
                ]),
                Grid::make()->schema([
                    Select::make('team')->name('Tim')->required()->options(UserTeam::values())->reactive(),
                    Select::make('position')->name('Jabatan')->required()->options(UserPosition::values())->reactive(),
                ]),
                Select::make('parent_id')->label('Atasan langsung')->options(function (Closure $get) {
                    return User::query()
                        ->where('branch_id', $get('branch_id'))
                        ->where('team', $get('team'))
                        ->where('position', '!=', UserPosition::staff)
                        ->pluck('name', 'id');
                })
                    ->exists('users', 'id')
                    ->searchable(),
                Grid::make()->schema([
                    TextInput::make('password')
                        ->required(fn (Component $livewire): bool => $livewire instanceof UserResource\Pages\CreateUser)
                        ->password()
                        ->same('passwordConfirmation')
                        ->dehydrated(fn (Component $livewire): bool => $livewire instanceof UserResource\Pages\CreateUser),
                    TextInput::make('passwordConfirmation')
                        ->required(fn (Component $livewire): bool => $livewire instanceof UserResource\Pages\CreateUser)
                        ->password()
                        ->dehydrated(false),
                    Select::make('timezone')->label('Timezone')->required()->searchable()->options(self::getTimezoneOptions()),
                    TextInput::make('referral_code')->label('Referral'),
                ]),
                Toggle::make('active')->label('Aktfikan user ini')->default(true),
                CheckboxList::make('roles')->required()->columns(4)->relationship('roles', 'name'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('branch.name')
                ->label('Cabang')
                ->searchable()
                ->sortable(),
            TextColumn::make('name')
                ->label('Nama')
                ->searchable()
                ->sortable(),
            TextColumn::make('email')
                ->label('Email')
                ->searchable()
                ->sortable(),
            TextColumn::make('team')
                ->label('Tim'),
            TextColumn::make('position')
                ->label('Jabatan'),
            TagsColumn::make('roles.name')
                ->label('Role'),
            IconColumn::make('deactivated_at')
                ->boolean()
                ->label('Status')
                ->getStateUsing(fn (?User $record) => $record?->getAttribute('deactivated_at') === null),
        ])->filters([
            Filter::make('active')
                ->label('Pilih user aktif')
                ->query(fn (Builder $query) => $query->orWhereNull('deactivated_at')),
            Filter::make('non_active')
                ->label('Pilih user tidak aktif')
                ->query(fn (Builder $query) => $query->orWhereNotNull('deactivated_at')),
            SelectFilter::make('branch_id')
                ->relationship('branch', 'name')
                ->label('Cabang'),
            SelectFilter::make('team')
                ->label('Tim')
                ->options(UserTeam::values()),
            SelectFilter::make('position')
                ->label('Jabatan')
                ->options(UserPosition::values()),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => UserResource\Pages\ListUsers::route('/'),
            'create' => UserResource\Pages\CreateUser::route('/create'),
            'edit' => UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }

    protected static function getTimezoneOptions(): array
    {
        return collect(DateTimeZone::listIdentifiers())->mapWithKeys(fn ($item) => [
            $item => $item,
        ])->all();
    }
}
