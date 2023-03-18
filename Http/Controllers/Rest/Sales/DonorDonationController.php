<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\JsonResponse;
use Ziswapp\Domain\Transaction\Model\Donor;
use Ziswapp\Job\SendDonationVerifiedNotification;
use Ziswapp\Domain\Transaction\Request\StoreDonationRequest;
use Ziswapp\Domain\Transaction\Action\StoreNewDonationAction;

final class DonorDonationController
{
    public function store(Donor $donor, StoreDonationRequest $request): JsonResponse
    {
        $donation = StoreNewDonationAction::handleFromRequest($request, $donor);

        SendDonationVerifiedNotification::dispatch($donation);

        return new JsonResponse('', 204);
    }
}
