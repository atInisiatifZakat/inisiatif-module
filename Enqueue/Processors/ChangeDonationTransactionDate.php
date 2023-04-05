<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Enqueue\Processors;

use Carbon\Carbon;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Ziswapp\Domain\Transaction\Model\Donation;
use Modules\Inisiatif\Enqueue\Contracts\HasConfirmationReference;

final class ChangeDonationTransactionDate implements Processor
{
    public function __construct(
        private readonly HasConfirmationReference $confirmation
    ) {
    }

    public function process(Message $message, Context $context): string
    {
        ['app' => $source, 'data' => $data] = \json_decode($message->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if ($this->shouldBeProcess($source, $data)) {
            /** @var Donation $donation */
            $donation = $this->confirmation->findUsingReference(
                $data['confirmation_id']
            );

            $donation->forceFill([
                'transaction_at' => Carbon::parse($data['transaction_date'])->timezone('Asia/Jakarta')->toDateString(),
            ])->save();

            return self::ACK;
        }

        return self::REJECT;
    }

    public function shouldBeProcess(string $source, array $data): bool
    {
        if ($source === 'edonation' && \array_key_exists('confirmation_id', $data) && $data['transaction_status'] === 'VERIFIED') {
            return $data['confirmation_id'] !== null && $this->confirmation->checkUsingReference($data['confirmation_id']);
        }

        return false;
    }
}
