<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Action;

use PhpLab\Application\Actions\Action;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use PhpLab\Modules\User\Domain\Command\RefreshToken\RefreshTokenCommand;
use PhpLab\Modules\User\Domain\Command\RefreshToken\RefreshTokenHandler;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/admin/auth/refresh-token',
    description: "Refresh user token",
    security: [
        ['bearerAuth' => []]
    ],
    tags: ['auth'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Success',
            content: new OA\JsonContent(
                required: ['success', 'data'],
                properties: [
                    new OA\Property(property:'success', type: 'boolean'),
                    new OA\Property(
                        property: 'data',
                        ref: '#/components/schemas/AdminAuthRefreshTokenRespond'
                    ),
                ],
                type: 'object',
            )
        ),
        new OA\Response(ref: '#/components/responses/Error', response: 'Error'),
    ]
)]
final class RefreshTokenAction extends Action
{
    public function __construct(
        private readonly RefreshTokenHandler $handler
    )
    {
    }

    protected function action(): Response
    {
        $jwtPayload = $this->request->getAttribute('jwtPayload');
        $refreshToken = $this->request->getAttribute('refreshToken');

        $respond = $this->handler->run(
            new RefreshTokenCommand($jwtPayload, $refreshToken)
        );

        return $this->respondWithData($respond);
    }
}
