<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Actions;

use Laravel\Sanctum\NewAccessToken;
use Modules\Inisiatif\Supports\Browser;
use Ziswapp\Domain\Foundation\Model\User;

final class NewAuthToken
{
    public function handle(User $user): NewAccessToken
    {
        $tokenName = \sprintf('%s/%s', Browser::platformName(), Browser::browserName());

        return $user->createToken($tokenName);
    }
}
