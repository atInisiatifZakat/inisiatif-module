<?php

namespace Modules\Inisiatif\Providers;

use Enqueue\SimpleClient\SimpleClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;
use Modules\Inisiatif\Enqueue\EnqueueBinding;
use Enqueue\LaravelQueue\Command\RoutesCommand;
use Enqueue\LaravelQueue\Command\ProduceCommand;
use Enqueue\LaravelQueue\Command\ConsumeCommand;
use Modules\Inisiatif\Enqueue\RegisterProcessor;
use Enqueue\LaravelQueue\Command\SetupBrokerCommand;

final class EnqueueServiceProvider extends ServiceProvider
{
    public function register(): void
    {
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
