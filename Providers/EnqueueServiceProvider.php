<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Providers;

use Enqueue\SimpleClient\SimpleClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Modules\Inisiatif\Enqueue\EnqueueBinding;
use Enqueue\LaravelQueue\Command\RoutesCommand;
use Enqueue\LaravelQueue\Command\ConsumeCommand;
use Enqueue\LaravelQueue\Command\ProduceCommand;
use Modules\Inisiatif\Enqueue\RegisterProcessor;
use Enqueue\LaravelQueue\Command\SetupBrokerCommand;
use Modules\Inisiatif\Extend\Repository\DonationRepository;
use Modules\Inisiatif\Enqueue\Contracts\HasConfirmationReference;

final class EnqueueServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(HasConfirmationReference::class, DonationRepository::class);

        $this->app->singleton(
            SimpleClient::class,
            static fn (Container $app) => EnqueueBinding::singleton($app)
        );

        $this->app->resolving(
            SimpleClient::class,
            static fn (SimpleClient $client, Container $app) => RegisterProcessor::register($client, $app)
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupBrokerCommand::class,
                ProduceCommand::class,
                RoutesCommand::class,
                ConsumeCommand::class,
            ]);
        }
    }
}
