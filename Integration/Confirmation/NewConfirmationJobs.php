<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Integration\Confirmation;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Ziswapp\Domain\Transaction\Model\Donation;
use Ziswapp\Domain\Foundation\Model\BankAccount;
use Ziswapp\Domain\Transaction\Model\DonationItem;
use Ziswapp\Domain\Foundation\Repository\FoundationRepository;
use Ziswapp\Domain\Foundation\Exception\FoundationProfileNotExist;
use Modules\Inisiatif\Integration\Confirmation\DataTransfers\ConfirmationItem;
use Modules\Inisiatif\Integration\Confirmation\DataTransfers\NewConfirmationData;

final class NewConfirmationJobs implements ShouldQueue, ShouldBeUnique
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;
    use SerializesModels;

    public function __construct(
        public readonly Donation $donation
    ) {
    }

    public function handle(Confirmation $confirmation, FoundationRepository $foundationRepository): void
    {
        $foundation = $foundationRepository->getFoundation();

        if ($foundation === null) {
            throw FoundationProfileNotExist::create();
        }

        $this->donation->loadMissing(['items', 'items.funding', 'items.program'])->loadMissingRelations('account');

        /** @var BankAccount $account */
        $account = $this->donation->getRelation('account');

        /** @var Collection<DonationItem> $items */
        $items = $this->donation->getRelation('items');

        $output = $confirmation->createConfirmation(new NewConfirmationData([
            'name' => $foundation->getAttribute('name'),
            'partner' => $foundation->getAttribute('inisiatif_ref_id'),
            'date' => $this->donation->getAttribute('created_at'),
            'paidAt' => $this->donation->getAttribute('transaction_at'),
            'channelName' => 'Bank Transfer',
            'bank' => $account->getAttribute('bank')?->getAttribute('name'),
            'accountNumber' => $account->getAttribute('number'),
            'fileUrl' => $this->donation->getAttribute('file_url'),
            'meta' => [
                [
                    'key' => 'identification_number',
                    'name' => 'Ziswapp Transaction Number',
                    'value' => $this->donation->getAttribute('identification_number'),
                ],
            ],
            'items' => $items->map(fn (DonationItem $item) => new ConfirmationItem([
                'type' => $item->getAttribute('funding')?->getAttribute('name'),
                'product' => $item->getAttribute('product')?->getAttribute('name') ?? $item->getAttribute('funding')?->getAttribute('name'),
                'amount' => $item->getAttribute('amount'),
            ])),
            'sourceId' => $this->donation->getAttribute(
                'id'
            ),
        ]));

        $this->donation->forceFill([
            'edonation_confirmation_id' => $output->id,
            'edonation_confirmation_number' => $output->identificationNumber,
        ])->update();
    }

    public function uniqueId(): string
    {
        return $this->donation->getKey();
    }
}
