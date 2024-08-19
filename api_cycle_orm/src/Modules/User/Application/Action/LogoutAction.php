<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Action;

use OpenApi\Attributes as OA;
use PhpLab\Application\Actions\Action;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use PhpLab\Modules\User\Domain\Command\LogoutUser\LogoutUserCommand;
use PhpLab\Modules\User\Domain\Command\LogoutUser\LogoutUserHandler;


#[OA\Post(
    path: '/admin/auth/logout',
    description: "Logout user on admin panel",
    security: [
        ['bearerAuth' => []]
    ],
    tags: ['auth'],
    responses: [
        new OA\Response(
            response: 201,
            description: 'Success',
            content: new OA\JsonContent(
                required: ['success', 'data'],
                properties: [
                    new OA\Property(property:'success', type: 'boolean'),
                ],
                type: 'object',
            )
        ),
        new OA\Response(ref: '#/components/responses/Error', response: 'Error'),
    ]
)]
final class LogoutAction extends Action
{
    public function __construct(
        private readonly LogoutUserHandler $handler
    )
    {
    }

    protected function action(): Response
    {
        $jwtPayload = $this->request->getAttribute('jwtPayload');
        $refreshToken = $this->request->getAttribute('refreshToken');

        $this->handler->run(
            new LogoutUserCommand($jwtPayload, $refreshToken)
        );

        return $this->respondWithData(null, 201);
    }
}
