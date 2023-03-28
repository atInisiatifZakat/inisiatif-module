<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Request;
use Ziswapp\Domain\Foundation\Model\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Ziswapp\Domain\Transaction\Repository\Contract\DonationRepository;

final class VerifiedAmountController
{
    public function show(Request $request, DonationRepository $donationRepository): JsonResource
    {
        /** @var User $user */
        $user = $request->user();

        $amount = $donationRepository->fetchAmountVerified(
            $user->getBranch(),
            $request->date('start', null, $user->getAttribute('timezone')),
            $request->date('end', null, $user->getAttribute('timezone')),
            $user
        );

        return JsonResource::make([
            'amount' => (float) $amount,
        ]);
    }
}
