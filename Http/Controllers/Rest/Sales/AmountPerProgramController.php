<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Request;
use Ziswapp\Domain\Foundation\Model\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Ziswapp\Domain\Transaction\Repository\Contract\DonationItemRepository;

final class AmountPerProgramController
{
    public function show(Request $request, DonationItemRepository $itemRepository): JsonResource
    {
        /** @var User $user */
        $user = $request->user();

        return JsonResource::make(
            $itemRepository->fetchAmountGroupByProgram(
                $user,
                null,
                $request->date('start'),
                $request->date('end')
            )->map(fn (mixed $item) => [
                'program' => $item->getAttribute('program'),
                'aggregate' => (float) $item->getAttribute('aggregate'),
            ])
        );
    }
}
