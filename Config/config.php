<?php

declare(strict_types=1);

return [
    'name' => 'Inisiatif',

    'mitra_ramadhan' => env('INISIATIF_RAMADHAN', false),

    'enqueue' => [
        'transport' => [
            'dsn' => env('INISIATIF_ENQUEUE_DSN', 'null://'),
        ],
        'extensions' => [
            'signal_extension' => true,
            'reply_extension' => false,
        ],
        'client' => [
            'default' => [
                'app_name' => 'ziswapp',
                'router_topic' => 'default',
                'router_queue' => 'default',
                'default_queue' => 'default',
            ],
            'edonation' => [
                'app_name' => 'edonation',
                'router_topic' => 'default',
                'router_queue' => 'default',
                'default_queue' => 'default',
            ],
        ],
    ],

    'processors' => [
        [
            'topic_name' => 'edonation-donation-updated',
            'processor_class' => Modules\Inisiatif\Enqueue\Processors\CreateDeposit::class,
        ],
        [
            'topic_name' => 'edonation-donation-updated',
            'processor_class' => Modules\Inisiatif\Enqueue\Processors\ChangeDonationTransactionDate::class,
        ],
        [
            'topic_name' => 'edonation-confirmation-verified',
            'processor_class' => Modules\Inisiatif\Enqueue\Processors\VerifiedDonation::class,
        ]
    ],
];
