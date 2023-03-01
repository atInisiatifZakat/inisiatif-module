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

    public function show(Donor $donor): JsonResource
    {
        return JsonResource::make(
            $donor->loadMissingRelations()
        );
    }
}
