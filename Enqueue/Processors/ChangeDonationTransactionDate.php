<?php

namespace Modules\Inisiatif\Enqueue\Processors;

use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Ziswapp\Domain\Transaction\Model\Donation;
use Modules\Inisiatif\Enqueue\Contracts\HasConfirmationReference;

final class ChangeDonationTransactionDate implements Processor
{
    public function __construct(
        private readonly HasConfirmationReference $confirmation
    )
    {
    }

    public function process(Message $message, Context $context): string
    {
        ['data' => $data] = \json_decode($message->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if($this->shouldBeProcess($data)) {
            /** @var Donation $donation */
            $donation = $this->confirmation->findUsingReference(
                $data['confirmation_id']
            );

            $donation->forceFill(['transaction_at' => $data['transaction_date']])->save();

            return self::ACK;
        }

        return self::REJECT;
    }

    public function shouldBeProcess(array $data): bool
    {
        return $this->confirmation->checkUsingReference(
            $data['confirmation_id']
        ) && $data['transaction_status'] === 'VERIFIED';
    }
}
