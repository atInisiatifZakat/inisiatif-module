<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Extend\Listeners;

use Ziswapp\Domain\Foundation\Model\BankAccount;
use Ziswapp\Domain\Transaction\Event\DonationWasCreated;
use Ziswapp\Domain\Transaction\Event\DonationWasUpdated;

final class FillDonationInisiatifVerified
{
    public function handle(DonationWasCreated|DonationWasUpdated $event): void
    {
        if ($event->donation->isTransfer()) {
            /** @var BankAccount $account */
            $account = $event->donation->load('account')->getRelation('account');

            $event->donation->forceFill([
                'is_inisiatif_verified' => (bool) $account->getAttribute('is_inisiatif_verified'),
            ])->save();
        }
    }
}
