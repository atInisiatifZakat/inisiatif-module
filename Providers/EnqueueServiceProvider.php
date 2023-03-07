<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Inisiatif\Enqueue\EnqueueBinding;
use Modules\Inisiatif\Extend\Repository\DonationRepository;
use Modules\Inisiatif\Console\EnqueueDonationConsumeCommand;
use Modules\Inisiatif\Enqueue\Contracts\HasConfirmationReference;

final class EnqueueServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(HasConfirmationReference::class, DonationRepository::class);

        $this->app->singleton('enqueue.client.edonation', static fn() => EnqueueBinding::makeClient(
            'edonation', \config('inisiatif.processors')
        ));

        if ($this->app->runningInConsole()) {
            $this->commands([
                EnqueueDonationConsumeCommand::class,
            ]);
        }
    }
}
