<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Request;
use Ziswapp\Domain\Transaction\Model\Donor;
use Illuminate\Http\Resources\Json\JsonResource;
use Ziswapp\Domain\Transaction\Repository\Contract\DonorRepository;

final class DonorController
{
    public function index(Request $request, DonorRepository $donorRepository): JsonResource
    {
        return JsonResource::collection(
            $donorRepository->filter($request)
        );
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
}
