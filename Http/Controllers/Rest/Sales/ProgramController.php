<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest\Sales;

use Illuminate\Http\Resources\Json\JsonResource;
use Ziswapp\Domain\Foundation\Repository\ProgramRepository;

final class ProgramController
{
    public function index(ProgramRepository $repository): JsonResource
    {
        return JsonResource::collection(
            $repository->fetchForSelectOption()
        );
    }
}
