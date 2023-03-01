<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Enqueue;

use Enqueue\SimpleClient\SimpleClient;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;

final class RegisterProcessor
{
    public static function register(SimpleClient $client, Container $app): SimpleClient
    {
        /** @var Repository $config */
        $config = $app->get('config');

        /** @var array $processors */
        $processors = $config->get('inisiatif.processors', []);

        foreach ($processors as $processor) {
            $client->bindTopic(
                $processor['topic_name'],
                $app->make($processor['processor_class']),
                $processor['processor_class']
            );
        }

        return $client;
    }
}
