<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Pages;

use Filament\Pages\Page;
use Filament\Facades\Filament;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Livewire\TemporaryUploadedFile;
use Ziswapp\Admin\Forms\AddressInput;
use Filament\Forms\Components\Select;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Ziswapp\Domain\Foundation\Model\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Ziswapp\Domain\Foundation\Model\Foundation;
use Modules\Inisiatif\Admin\Forms\Components\InisiatifRefNumberInput;

/**
 * @property ComponentContainer $form
 */
final class FoundationPage extends Page
{
    use InteractsWithForms;

    public mixed $data;

    public ?Foundation $foundation = null;

    protected static ?string $slug = 'foundation';

    protected static ?string $navigationLabel = 'Profile';

    protected static ?string $title = 'Profile';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static string $view = 'filament.pages.foundation.foundation';

    public function mount(): void
    {
        /** @var User $user */
        $user = Filament::auth()->user();

        \abort_unless($user->getAttribute('is_super_admin') || $user->hasPermissionTo('menu.foundation'), 403);

        $this->foundation = Foundation::query()->first();

        $this->data = $this->foundation?->toArray();

        $this->form->fill($this->data)->model($this->foundation);
    }

    public static function registerNavigationItems(): void
    {
        if (! static::shouldRegisterNavigation()) {
            return;
        }

        /** @var User $user */
        $user = Filament::auth()->user();

        if ($user->getAttribute('is_super_admin') || $user->hasPermissionTo('menu.foundation')) {
            Filament::registerNavigationItems(static::getNavigationItems());
        }
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        if ($this->foundation) {
            $this->foundation->forceFill($data)->update();
        } else {
            Foundation::query()->forceCreate($data);
        }

        $this->notify('success', $this->getSavedNotificationMessage());
    }

    protected function getSavedNotificationMessage(): ?string
    {
        return __('filament::resources/pages/edit-record.messages.saved');
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([
                Grid::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->autocomplete('off')
                        ->label('Nama')
                        ->placeholder('Nama yayasan disini'),
                    TextInput::make('phone')
                        ->autocomplete('off')
                        ->label('Phone')
                        ->placeholder('Nomor telepon yayasan disini'),
                ]),
                FileUpload::make('logo')->directory('uploads')->image()->visibility('public')->getUploadedFileNameForStorageUsing(
                    fn (TemporaryUploadedFile $file) => str($file->getClientOriginalName())->prepend('logo-')
                ),
                AddressInput::make($this->foundation ?? Foundation::class),
                Grid::make()->schema([
                    InisiatifRefNumberInput::make()
                        ->nullable()
                        ->hidden(\config('inisiatif.mitra_ramadhan'))
                        ->helperText('Diisi dengan partner id'),
                    Select::make('user_id')
                        ->options(User::query()->pluck('name', 'id'))
                        ->searchable()
                        ->nullable()
                        ->hidden(\config('inisiatif.mitra_ramadhan'))
                        ->helperText('Wajib diisi untuk sinkronisasi'),
                ]),
            ]),
        ];
    }
}
