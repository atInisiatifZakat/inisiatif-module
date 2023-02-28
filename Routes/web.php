<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Modules\Inisiatif\Http\Controllers\DonationController;
use Modules\Inisiatif\Http\Controllers\DonationVerifiedController;

return static function (Router $router): void {
    $router->get('/donation/{donation}/edit', [DonationController::class, 'edit']);
    $router->get('/donation/{donation}', [DonationController::class, 'show']);
    $router->patch('/donation/{donation}', [DonationController::class, 'update']);
    $router->patch('/donation/{donation}/verify', [DonationVerifiedController::class, 'store']);
};
