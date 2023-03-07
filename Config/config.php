<?php

declare(strict_types=1);

return [
    'name' => 'Inisiatif',

    'mitra_ramadhan' => env('INISIATIF_RAMADHAN', false),

    'enqueue' => [
        'transport' => [
            'dsn' => env('INISIATIF_ENQUEUE_DSN', 'null://'),
        ],
        'client' => [
            'router_topic' => 'default',
            'router_queue' => 'default',
            'default_queue' => 'default',
        ],
        'extensions' => [
            'signal_extension' => true,
            'reply_extension' => false,
        ],
    ],

    'processors' => [
        [
            'topic_name' => 'edonation.donation.updated',
            'processor_class' => Modules\Inisiatif\Enqueue\Processors\HandleVerifiedDonation::class,
        ]
    ],
];
