<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Enqueue;

use Illuminate\Support\Arr;
use Interop\Queue\Processor;
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
            $processorClass = $processor['processor_class'];

            if (\in_array(Processor::class, Arr::wrap(\class_implements($processorClass)), true)) {
                $client->bindTopic(
                    $processor['topic_name'],
                    $app->make($processor['processor_class']),
                    $processor['processor_class']
                );
            }
        }

        return $client;
    }
}
