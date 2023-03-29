<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Console;

use Enqueue\Symfony\Client\SimpleConsumeCommand;

final class EnqueueDonationConsumeCommand extends SimpleConsumeCommand
{
    public function __construct()
    {
        $client = app('enqueue.client.edonation');

        parent::__construct(
            $client->getQueueConsumer(),
            $client->getDriver(),
            $client->getDelegateProcessor()
        );

        $this->setAliases([]);
        $this->setName('enqueue:consume:edonation');
        $this->setDescription('A `edonation` client worker that processes messages.');
    }
}
