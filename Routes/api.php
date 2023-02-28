<?php

declare(strict_types=1);

use Illuminate\Routing\Router;

return static function (Router $router): void {
    $router->group([
        'middleware' => 'auth:sanctum',
    ], static function (Router $router): void {
    });
};
