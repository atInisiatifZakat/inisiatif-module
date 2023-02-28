<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Extend\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ziswapp\Domain\Transaction\Event\DonationWasCreated;
use Ziswapp\Domain\Transaction\Event\DonationWasUpdated;
use Modules\Inisiatif\Integration\Confirmation\NewConfirmationJobs;

final class CreateNewConfirmation implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(DonationWasCreated|DonationWasUpdated $event): void
    {
        if ($event->donation->isTransfer() && $event->donation->getAttribute('is_inisiatif_verified') === true) {
            dispatch(new NewConfirmationJobs($event->donation))->afterCommit();
        }
    }

    public function shouldQueue(DonationWasCreated|DonationWasUpdated $event): bool
    {
        return $event->donation->isTransfer() && $event->donation->getAttribute('is_inisiatif_verified') === true;
    }
}
