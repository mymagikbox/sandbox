<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;

use PhpLab\Application\Actions\Action;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use PhpLab\Application\Interface\Validator\ValidatorInterface;
use PhpLab\Modules\User\Domain\Command\DeleteUser\DeleteUserCommand;
use PhpLab\Modules\User\Domain\Command\DeleteUser\DeleteUserHandler;
use OpenApi\Attributes as OA;

#[OA\Delete(
    path: '/admin/user/{id}/delete',
    description: "Delete user from admin panel",
    security: [["bearerAuth" => []]],
    tags: ['user'],
    parameters: [
        new OA\Parameter(ref: '#/components/parameters/IdInPath'),
    ],
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
final class DeleteUserAction extends Action
{
    public function __construct(
        private readonly DeleteUserHandler $handler,
        private readonly ValidatorInterface $validator,
    )
    {
    }

    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        $command = new DeleteUserCommand(
            $this->request->getAttribute('jwtPayload'),
            $userId
        );

        $this->validator->validate($command);

        $this->handler->run($command);

        return $this->respondWithData(null, 201);
    }
}
