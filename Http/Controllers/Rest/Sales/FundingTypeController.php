<?php

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Resources\Json\JsonResource;
use Ziswapp\Domain\Foundation\Repository\BankAccountRepository;
use Ziswapp\Domain\Foundation\Repository\FundingTypeRepository;

final class FundingTypeController
{
    public function index(FundingTypeRepository $repository): JsonResource
    {
        return JsonResource::collection(
            $repository->fetchForSelectOption()
        );
    }
}
