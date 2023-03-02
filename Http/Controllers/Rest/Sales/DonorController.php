<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Ziswapp\Domain\Transaction\Model\Donor;
use Illuminate\Http\Resources\Json\JsonResource;
use Ziswapp\Domain\Transaction\Action\UpdateDonorAction;
use Ziswapp\Domain\Transaction\Request\StoreDonorRequest;
use Ziswapp\Domain\Transaction\Action\StoreNewDonorAction;
use Ziswapp\Domain\Transaction\Request\UpdateDonorRequest;
use Ziswapp\Domain\Transaction\Repository\Contract\DonorRepository;

final class DonorController
{
    public function index(Request $request, DonorRepository $donorRepository): JsonResource
    {
        return JsonResource::collection(
            $donorRepository->filter($request)
        );
    }

    public function store(StoreDonorRequest $request): JsonResponse
    {
        StoreNewDonorAction::handleFromRequest($request);

        return new JsonResponse('', 204);
    }

    public function show(string $donor, DonorRepository $donorRepository): JsonResource
    {
        /** @var Donor|null $model */
        $model = $donorRepository->findUsingUuid($donor);

        if ($model === null) {
            abort(404);
        }

        return JsonResource::make(
            $model->loadMissingRelations()
        );
    }

    public function update(Donor $donor, UpdateDonorRequest $request): JsonResponse
    {
        UpdateDonorAction::handleFromRequest($donor, $request);

        return new JsonResponse('', 204);
    }
}
