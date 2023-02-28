<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Providers;

use Illuminate\Foundation\Support\Providers;
use Ziswapp\Domain\Transaction\Event\DonationWasCreated;
use Ziswapp\Domain\Transaction\Event\DonationWasUpdated;
use Modules\Inisiatif\Extend\Listeners\CreateNewConfirmation;
use Modules\Inisiatif\Extend\Listeners\FillDonationInisiatifVerified;

final class EventServiceProvider extends Providers\EventServiceProvider
{
    protected $listen = [
        DonationWasUpdated::class => [
            FillDonationInisiatifVerified::class,
            CreateNewConfirmation::class,
        ],
        DonationWasCreated::class => [
            FillDonationInisiatifVerified::class,
            CreateNewConfirmation::class,
        ],
    ];
}
