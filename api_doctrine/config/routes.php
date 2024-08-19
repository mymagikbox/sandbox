<?php
declare(strict_types=1);

use Slim\App;
use PhpLab\Application\Middleware\AuthMiddleware;
use PhpLab\Application\Middleware\RefreshTokenMiddleware;
use PhpLab\Modules\User\Application\Actions\CreateUserAction;
use PhpLab\Modules\User\Application\Actions\DeleteUserAction;
use PhpLab\Modules\User\Application\Actions\FormOptionsAction;
use PhpLab\Modules\User\Application\Actions\ListUsersAction;
use PhpLab\Modules\User\Application\Actions\LogoutAction;
use PhpLab\Modules\User\Application\Actions\RefreshTokenAction;
use PhpLab\Modules\User\Application\Actions\UpdateUserAction;
use PhpLab\Modules\User\Application\Actions\ViewUserAction;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Exception\HttpNotFoundException;
use PhpLab\Modules\User\Application\Actions\LoginAction;


return function (App $app) {

    $app->options('/{routes:.*}', function (Request $request, Response $response) : Response
    {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->add(function (Request $request, $handler): Response
    {
        $exposedHeaders = ['x-page', 'x-total-items', 'x-per-page'];
        $response = $handler->handle($request);

        // This variable should be set to the allowed host from which your API can be accessed with
        $origin = $_ENV['ADMIN_URL'];

        return $response
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader(
                'Access-Control-Allow-Headers',
                'X-Requested-With, Content-Type, Accept, Origin, Authorization',
            )
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->withAddedHeader('Cache-Control', 'post-check=0, pre-check=0')
            ->withAddedHeader('Access-Control-Expose-Headers', implode(", ", $exposedHeaders))
            ->withHeader('Pragma', 'no-cache');
    });

    $app->get('/', function (Request $request, Response $response) {
        $response
            ->getBody()
            ->write('API main page');

        return $response;
    });

    $app->group('/admin', function (Group $group)
    {
        $group->group('/auth', function (Group $group) {
            $group->post('/login', LoginAction::class);
            $group->post('/logout', LogoutAction::class)->add(RefreshTokenMiddleware::class);
            $group->post('/refresh-token', RefreshTokenAction::class)->add(RefreshTokenMiddleware::class);
        });

        $group->group('/user', function (Group $group) {
            $group->get('', ListUsersAction::class);
            $group->get('/{id:\d+}/view', ViewUserAction::class);
            $group->patch('/{id:\d+}/update', UpdateUserAction::class);
            $group->post('/create', CreateUserAction::class);
            $group->delete('/delete', DeleteUserAction::class);
            $group->get('/form-options', FormOptionsAction::class);
        })->add(AuthMiddleware::class);
    });

    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function (Request $request): void {
            throw new HttpNotFoundException($request);
        }
    );
};
