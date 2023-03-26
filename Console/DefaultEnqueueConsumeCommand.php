<?php

namespace Modules\Inisiatif\Console;

use Enqueue\Symfony\Client\SimpleConsumeCommand;

class DefaultEnqueueConsumeCommand extends SimpleConsumeCommand
{
    public function __construct()
    {
        $client = app('enqueue.client.default');

        parent::__construct(
            $client->getQueueConsumer(),
            $client->getDriver(),
            $client->getDelegateProcessor()
        );

        $this->setAliases([]);
        $this->setName('enqueue:consume:default');
        $this->setDescription("A `default` client worker that processes messages.");
    }
}
