<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Modules\Inisiatif\Integration\Confirmation\Credentials;
use Modules\Inisiatif\Integration\Confirmation\Confirmation;

final class InisiatifServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Inisiatif';

    protected string $moduleNameLower = 'inisiatif';

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(
            \module_path($this->moduleName, 'Database/Migrations')
        );
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->registerRepository();
        $this->registerConfirmation();
    }

    public function registerViews(): void
    {
        $viewPath = \resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = \module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(\array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    public function registerTranslations(): void
    {
        $langPath = \resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            \module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');

        $this->mergeConfigFrom(
            \module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    protected function registerRepository(): void
    {
        $this->app->singleton(
            \Ziswapp\Domain\Transaction\Repository\Contract\DonationRepository::class,
            \Modules\Inisiatif\Extend\Repository\DonationRepository::class,
        );
    }

    protected function registerConfirmation(): void
    {
        $this->app->singleton(Confirmation::class, static fn (Container $app) => new Confirmation(
            new Credentials(
                $app->make('config')->get('services.confirmation.token', ''),
                $app->environment('production')
            )
        ));
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];

        /** @psalm-suppress PossibleRawObjectIteration */
        foreach (\config('view.paths') as $path) {
            if (\is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }
}
