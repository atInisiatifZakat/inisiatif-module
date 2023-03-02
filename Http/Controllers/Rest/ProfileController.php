<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest;

use Illuminate\Http\Request;
use Ziswapp\Domain\Foundation\Model\User;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProfileController
{
    public function show(Request $request): JsonResource
    {
        /** @var User $user */
        $user = $request->user();

        return new JsonResource(
            $user->only(['id', 'name', 'email', 'branch_id'])
        );
    }
}
