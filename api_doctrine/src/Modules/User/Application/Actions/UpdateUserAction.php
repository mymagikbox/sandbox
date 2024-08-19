<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;


use PhpLab\Application\Actions\Action;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use PhpLab\Application\Interface\Hydration\DenormalizerInterface;
use PhpLab\Application\Interface\Validator\ValidatorInterface;
use PhpLab\Modules\User\Domain\Command\UpdateUser\UpdateUserCommand;
use PhpLab\Modules\User\Domain\Command\UpdateUser\UpdateUserHandler;

use OpenApi\Attributes as OA;

#[OA\Patch(
    path: '/admin/user/{id}/update',
    description: "Update user from admin panel",
    security: [["bearerAuth" => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/AdminUserUpdateCommand')
    ),
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
final class UpdateUserAction extends Action
{
    public function __construct(
        private readonly DenormalizerInterface $denormalizer,
        private readonly ValidatorInterface $validator,
        private readonly UpdateUserHandler $handler
    )
    {
    }

    protected function action(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $inputData = (array) $this->request->getParsedBody();

        $command = $this->denormalizer->denormalize(UpdateUserCommand::class, $inputData);

        $this->validator->validate($command);

        $this->handler->run($userId, $command);

        return $this->respondWithData(null, 201);
    }
}
