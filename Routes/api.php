<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Modules\Inisiatif\Http\Controllers\Rest\ProfileController;
use Modules\Inisiatif\Http\Controllers\Rest\TokenAuthController;
use Modules\Inisiatif\Http\Controllers\Rest\Sales\DonorController;
use Modules\Inisiatif\Http\Controllers\Rest\Sales\DonationController;
use Modules\Inisiatif\Http\Controllers\Rest\Sales\VerifiedAmountController;
use Modules\Inisiatif\Http\Controllers\Rest\Sales\DonationCancelController;
use Modules\Inisiatif\Http\Controllers\Rest\Sales\DonationInvoiceController;
use Modules\Inisiatif\Http\Controllers\Rest\Sales\AmountPerProgramController;
use Modules\Inisiatif\Http\Controllers\Rest\Sales\AmountPerFundingTypeController;

return static function (Router $router): void {
    $router->post('/auth/token', [TokenAuthController::class, 'store']);

    $router->group([
        'middleware' => 'auth:sanctum',
    ], static function (Router $router): void {
        $router->get('/profile', [ProfileController::class, 'show']);
        $router->delete('/auth/token', [TokenAuthController::class, 'delete']);
    });

    $router->middleware('auth:sanctum')->prefix('/sales')->group(static function (Router $router): void {
        $router->get('/amount/verified', [VerifiedAmountController::class, 'show']);
        $router->get('/amount/funding', [AmountPerFundingTypeController::class, 'show']);
        $router->get('/amount/program', [AmountPerProgramController::class, 'show']);

        $router->get('/donor', [DonorController::class, 'index']);
        $router->get('/donor/{donor}', [DonorController::class, 'show']);

        $router->get('/donation', [DonationController::class, 'index']);
        $router->get('/donation/{donation}', [DonationController::class, 'show']);
        $router->get('/donation/{donation}/invoice', [DonationInvoiceController::class, 'show']);
        $router->patch('/donation/{donation}/cancel', [DonationCancelController::class, 'store']);
    });
};
