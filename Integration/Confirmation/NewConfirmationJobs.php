<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Integration\Confirmation;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Ziswapp\Domain\Foundation\Model\Branch;
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
        $foundationOrBranch = \config('inisiatif.mitra_ramadhan') ?
            $this->donation->loadMissing('branch')->getRelation('branch') :
            $foundationRepository->getFoundation();

        if ($foundationOrBranch === null) {
            throw FoundationProfileNotExist::create();
        }

        $foundationName = $foundationOrBranch->getAttribute('name');
        $inisiatifRefId = $foundationOrBranch->getAttribute('inisiatif_ref_id');

        if ($foundationOrBranch instanceof Branch && $foundationOrBranch->getAttribute('type') === 'KCP') {
            /** @var Branch $parent */
            $parent = $foundationOrBranch->loadMissing('parent')->getRelation('parent');

            $foundationName = $parent->getAttribute('name').' - '.$foundationOrBranch->getAttribute('name');
            $inisiatifRefId = $foundationOrBranch->getAttribute('inisiatif_ref_id') ?: $parent->getAttribute('inisiatif_ref_id');
        }

        $this->donation->loadMissing(['items', 'items.funding', 'items.program'])->loadMissingRelations('account');

        /** @var BankAccount $account */
        $account = $this->donation->getRelation('account');

        /** @var Collection<DonationItem> $items */
        $items = $this->donation->getRelation('items');

        $output = $confirmation->createConfirmation(new NewConfirmationData([
            'name' => $foundationName,
            'partner' => $inisiatifRefId,
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
                'type' => $item->getAttribute('funding')->getAttribute('name'),
                'product' => $item->getAttribute('program')?->getAttribute('name') ?? $item->getAttribute('funding')->getAttribute('name'),
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
