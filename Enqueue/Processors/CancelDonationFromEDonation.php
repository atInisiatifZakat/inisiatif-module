<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Enqueue\Processors;

use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Ziswapp\Domain\Transaction\Model\Donation;
use Ziswapp\Domain\Transaction\Action\DonationCancelAction;
use Modules\Inisiatif\Enqueue\Contracts\HasConfirmationReference;

final class CancelDonationFromEDonation implements Processor
{
    public function __construct(
        private readonly DonationCancelAction $cancel,
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

            $this->cancel->handle($donation);

            return self::ACK;
        }

        return self::REJECT;
    }

    public function shouldBeProcess(string $source, array $data): bool
    {
        if ($source === 'edonation' && \array_key_exists('confirmation_id', $data) && $data['transaction_status'] === 'CANCEL') {
            return $data['confirmation_id'] !== null && $this->confirmation->checkUsingReference($data['confirmation_id']);
        }

        return false;
    }
}
