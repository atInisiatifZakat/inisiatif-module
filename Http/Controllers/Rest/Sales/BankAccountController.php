<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Resources\Json\JsonResource;
use Ziswapp\Domain\Foundation\Repository\BankAccountRepository;

final class BankAccountController
{
    public function index(BankAccountRepository $repository): JsonResource
    {
        return JsonResource::collection(
            $repository->fetchForSelectOption()
        );
    }
}
