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

        foreach ($processors as $processor) {
            $processorClass = $processor['processor_class'];

            if (\in_array(Processor::class, Arr::wrap(\class_implements($processorClass)), true)) {
                $simpleClient->bindTopic(
                    $processor['topic_name'], app($processor['processor_class']), $processor['processor_class']
                );
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
