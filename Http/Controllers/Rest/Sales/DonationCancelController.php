<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Ziswapp\Domain\Transaction\Model\Donation;
use Ziswapp\Domain\Transaction\Action\DonationCancelAction;

final class DonationCancelController
{
    public function store(Donation $donation, Request $request): JsonResponse
    {
        if ($donation->isCancel()) {
            throw ValidationException::withMessages([
                'status' => [
                    'Invalid donation status',
                ],
            ]);
        }

        DonationCancelAction::handleFromRequest($donation, $request);

        return new JsonResponse('', 204);
    }
}
