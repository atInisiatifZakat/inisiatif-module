<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Integration\Confirmation\DataTransfers;

use DateTimeInterface;
use Spatie\DataTransferObject\Attributes\MapTo;
use Ziswapp\DataTransfer\Caster\DateTimeCaster;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;

final class NewConfirmationData extends DataTransferObject
{
    #[MapTo('partner_id')]
    public mixed $partner = null;

    public string $name;

    #[CastWith(DateTimeCaster::class)]
    public DateTimeInterface $date;

    #[MapTo('payment_channel_name')]
    public string $channelName;

    #[MapTo('bank_name')]
    public string $bank;

    #[MapTo('bank_account_number')]
    public string $accountNumber;

    #[MapTo('file_url')]
    public string $fileUrl;

    #[MapTo('meta')]
    #[CastWith(ArrayCaster::class, ConfirmationMeta::class)]
    public array $meta = [];

    #[MapTo('items')]
    #[CastWith(ArrayCaster::class, ConfirmationItem::class)]
    public array $items = [];

    public string $source = 'ziswapp';

    #[MapTo('source_id')]
    public mixed $sourceId = null;

    #[MapTo('paid_at')]
    #[CastWith(DateTimeCaster::class)]
    public ?DateTimeInterface $paidAt = null;
}
