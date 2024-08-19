<?php

declare(strict_types=1);

use PhpLab\Application\Middleware\DenormalizationExceptionHandler;
use PhpLab\Application\Middleware\SessionMiddleware;
use PhpLab\Application\Middleware\I18nMiddleware;
use PhpLab\Application\Middleware\ValidationExceptionHandler;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->add(I18nMiddleware::class);
    $app->add(DenormalizationExceptionHandler::class);
    $app->add(ValidationExceptionHandler::class);
};
