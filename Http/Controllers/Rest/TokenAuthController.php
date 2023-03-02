<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Http\Controllers\Rest;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Auth\Factory;
use Laravel\Sanctum\PersonalAccessToken;
use Ziswapp\Domain\Foundation\Model\User;
use Modules\Inisiatif\Actions\NewAuthToken;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Inisiatif\Http\Requests\AuthTokenRequest;

final class TokenAuthController
{
    public function store(AuthTokenRequest $request, Factory $auth, NewAuthToken $authToken): JsonResource
    {
        $login = $auth->guard()->attempt($request->only(['email', 'password']), true);

        \throw_unless($login, ValidationException::withMessages([
            'email' => [
                'These credentials do not match our records.',
            ],
        ]));

        /** @var User $user */
        $user = $auth->guard()->user();

        $newToken = $authToken->handle($user);

        return new JsonResource([
            'token' => Crypt::encrypt($newToken->plainTextToken),
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        if ($request->bearerToken()) {
            $token = Crypt::decrypt($request->bearerToken());

            $accessToken = PersonalAccessToken::findToken($token);

            $accessToken?->forceDelete();
        }

        return new JsonResponse('', 204);
    }
}
