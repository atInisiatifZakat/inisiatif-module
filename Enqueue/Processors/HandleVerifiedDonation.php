<?php

namespace Modules\Inisiatif\Enqueue\Processors;

use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Illuminate\Support\Facades\Log;

final class HandleVerifiedDonation implements Processor
{
    public function process(Message $message, Context $context): string
    {
        Log::debug(
            \sprintf('Process message [%s]' . $message->getMessageId()),
            \json_decode($message->getBody(), true, 512, JSON_THROW_ON_ERROR)
        );

        return self::ACK;
    }
}
