<?php
declare(strict_types=1);

use Slim\App;
use PhpLab\Application\Middleware\ExceptionMiddleware;
use PhpLab\Application\Middleware\ValidationMiddleware;

return function (App $app) {
    // $app->add(SessionMiddleware::class);

    // Add Body Parsing Middleware
    $app->addBodyParsingMiddleware();


    // The RoutingMiddleware should be added after our CORS middleware so routing is performed first
    $app->addRoutingMiddleware();

    $app->add(ValidationMiddleware::class);
    $app->add(ExceptionMiddleware::class);
};
