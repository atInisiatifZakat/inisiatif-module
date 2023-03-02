<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Ziswapp\Domain\Transaction\Repository\Contract\DonorRepository;

final class DonorChooseController
{
    public function index(Request $request, DonorRepository $donorRepository): JsonResource
    {
        return JsonResource::collection(
            $request->query('q') ? $donorRepository->filterForDonation($request) : new Paginator([], 15),
        );
    }
}
