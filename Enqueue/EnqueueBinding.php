<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Enqueue;

use Psr\Log\LoggerInterface;
use Enqueue\SimpleClient\SimpleClient;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;

final class EnqueueBinding
{
    public static function singleton(Container $app): SimpleClient
    {
        /** @var Repository $config */
        $config = $app->get('config');

        /** @var LoggerInterface $logger */
        $logger = $app->make('log');

        return new SimpleClient($config->get('inisiatif.enqueue', self::defaultConfig()), $logger);
    }

    private static function defaultConfig(): array
    {
        return [
            'transport' => [
                'dsn' => 'null://',
            ],
            'client' => [
                'router_topic' => 'default',
                'router_queue' => 'default',
                'default_queue' => 'default',
            ],
        ];
    }
}
