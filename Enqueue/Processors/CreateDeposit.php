<?php

namespace Modules\Inisiatif\Enqueue\Processors;

use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Illuminate\Support\Facades\Log;

final class CreateDeposit implements Processor
{
    public function process(Message $message, Context $context): string
    {
        $body = \json_decode($message->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if ($body['app']) {
            Log::debug(\sprintf('Process message [%s]', $message->getMessageId()), $body);

            return self::ACK;
        }

        return self::ACK;
    }

    public function shouldBeProcess(string $source, array $data): bool
    {
        return $source === 'edonation';
    }
}