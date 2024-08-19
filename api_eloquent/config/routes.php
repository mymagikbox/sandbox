<?php
declare(strict_types=1);

use PhpLab\Modules\User\Application\Actions\CreateUserAction;
use PhpLab\Modules\User\Application\Actions\DeleteUserAction;
use PhpLab\Modules\User\Application\Actions\FormOptionsAction;
use PhpLab\Modules\User\Application\Actions\ListUsersAction;
use PhpLab\Modules\User\Application\Actions\LoginAction;
use PhpLab\Modules\User\Application\Actions\LogoutAction;
use PhpLab\Modules\User\Application\Actions\RefreshTokenAction;
use PhpLab\Modules\User\Application\Actions\UpdateUserAction;
use PhpLab\Modules\User\Application\Actions\ViewUserAction;
use PhpLab\Application\Middleware\AuthMiddleware;
use PhpLab\Application\Middleware\RefreshTokenMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('API main page');
        return $response;
    });

    $app->group('/admin', function (Group $group) {
        $group->group('/auth', function (Group $group) {
            $group->post('/login', LoginAction::class);
            $group->post('/logout', LogoutAction::class)->add(RefreshTokenMiddleware::class);
            $group->post('/refresh-token', RefreshTokenAction::class)->add(RefreshTokenMiddleware::class);
        });

        $group->group('/users', function (Group $group) {
            $group->get('', ListUsersAction::class);
            $group->get('/{id:\d+}/view', ViewUserAction::class);
            $group->patch('/{id:\d+}/update', UpdateUserAction::class);
            $group->post('/create', CreateUserAction::class);
            $group->delete('/delete', DeleteUserAction::class);
            $group->get('/form-options', FormOptionsAction::class);
        })->add(AuthMiddleware::class);
    });

};
