<?php

namespace Modules\Inisiatif\Enqueue\Processors;

use Interop\Queue\Message;
use Interop\Queue\Context;
use Interop\Queue\Processor;
use Ziswapp\Domain\Transaction\Model\Donation;
use Ziswapp\Domain\Transaction\Action\DonationVerifiedAction;
use Modules\Inisiatif\Enqueue\Contracts\HasConfirmationReference;

final class VerifiedDonation implements Processor
{
    public function __construct(
        private readonly DonationVerifiedAction $verified,
        private readonly HasConfirmationReference $confirmation
    )
    {
    }

    public function process(Message $message, Context $context): string
    {
        ['app' => $source, 'data' => $data] = \json_decode($message->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if ($this->shouldBeProcess($source, $data)) {
            /** @var Donation $donation */
            $donation = $this->confirmation->findUsingReference(
                $data['id']
            );

            $this->verified->handle($donation);

            return self::ACK;
        }

        return self::REJECT;
    }

    public function shouldBeProcess(string $source, array $data): bool
    {
        return $source === 'edonation' && $this->confirmation->checkUsingReference(
            $data['id']
        );
    }
}
