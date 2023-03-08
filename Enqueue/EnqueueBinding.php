<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Enqueue;

use Illuminate\Support\Arr;
use Psr\Log\LoggerInterface;
use Interop\Queue\Processor;
use Enqueue\SimpleClient\SimpleClient;

final class EnqueueBinding
{
    public static function makeClient(string $client = 'default', array $processors = []): SimpleClient
    {
        $configs = \array_merge(Arr::only(config('inisiatif.enqueue'), ['transport', 'extensions']), [
            'client' => \config('inisiatif.enqueue.client.' . $client, self::defaultClientConfig())
        ]);

        $simpleClient = new SimpleClient($configs, app(LoggerInterface::class));

        foreach ($processors as $topicName => $processorClasses) {
            foreach ($processorClasses as $processorClass) {
                if (\in_array(Processor::class, Arr::wrap(\class_implements($processorClass)), true)) {
                    $simpleClient->bindTopic($topicName, app($processorClass), $processorClass);
                }
            }
        }

        return $simpleClient;
    }

    private static function defaultClientConfig(): array
    {
        return [
            'router_topic' => 'default',
            'router_queue' => 'default',
            'default_queue' => 'default',
        ];
    }
}
