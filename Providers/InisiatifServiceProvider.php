<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Providers;

use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;
use Ziswapp\Domain\Foundation\Model\User;
use Ziswapp\Domain\Foundation\Model\Branch;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Encryption\DecryptException;
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

        Sanctum::getAccessTokenFromRequestUsing(
            static function (Request $request): ?string {
                try {
                    return Crypt::decrypt($request->bearerToken() ?? $request->query('token'));
                } catch (DecryptException) {
                    return null;
                }
            }
        );
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(EnqueueServiceProvider::class);

        Branch::resolveRelationUsing('user', static fn(Branch $branch) => $branch->belongsTo(User::class, 'user_id'));

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
