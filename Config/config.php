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
                'app_name' => env('INISIATIF_ENQUEUE_NAME', 'ziswapp'),
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
        'edonation-donation-saved' => [
            Modules\Inisiatif\Enqueue\Processors\ChangeDonationTransactionDate::class,
            Modules\Inisiatif\Enqueue\Processors\CancelDonationFromEDonation::class,

            Modules\Deposit\Enqueue\Processors\CreateDepositFromDonation::class,
            Modules\Deposit\Enqueue\Processors\CancelDepositFromDonation::class,
        ],
        'edonation-confirmation-verified' => [
            Modules\Inisiatif\Enqueue\Processors\VerifiedDonationFromEDonation::class,
            Modules\Inisiatif\Enqueue\Processors\VerifiedDonationFromEDonation::class
        ],
    ],
];
