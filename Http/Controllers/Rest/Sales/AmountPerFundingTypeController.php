<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Request;
use Ziswapp\Domain\Foundation\Model\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Ziswapp\Domain\Transaction\Repository\Contract\DonationItemRepository;

final class AmountPerFundingTypeController
{
    public function show(Request $request, DonationItemRepository $itemRepository): JsonResource
    {
        /** @var User $user */
        $user = $request->user();

        return JsonResource::make(
            $itemRepository->fetchAmountGroupByFundingType(
                $user->getBranch(),
                $request->date('start', null, $user->getAttribute('timezone')),
                $request->date('end', null, $user->getAttribute('timezone')),
                $user
            )->map(fn (mixed $item) => [
                'funding_type' => $item->getAttribute('funding_type'),
                'aggregate' => (float) $item->getAttribute('aggregate'),
            ])
        );
    }
}
