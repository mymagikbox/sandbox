<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Action;

use PhpLab\Application\Actions\Action;
use PhpLab\Application\Interface\Validator\ValidatorInterface;
use PhpLab\Application\Interface\Hydration\DenormalizerInterface;
use PhpLab\Modules\User\Domain\Command\CreateUser\CreateUserCommand;
use PhpLab\Modules\User\Domain\Command\CreateUser\CreateUserHandler;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use OpenApi\Attributes as OA;



#[OA\Post(
    path: '/admin/user/create',
    description: "Create user from admin panel",
    security: [["bearerAuth" => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/AdminUserCreateCommand')
    ),
    tags: ['user'],
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
// File shema
// https://stackoverflow.com/questions/72119745/how-to-specify-to-swagger-php-that-my-parameter-is-going-to-be-a-file
final class CreateUserAction extends Action
{
    public function __construct(
        private readonly DenormalizerInterface $denormalizer,
        private readonly ValidatorInterface $validator,
        private readonly CreateUserHandler $handler
    ) {
    }

    protected function action(): Response
    {
        $inputData = (array) $this->request->getParsedBody();

        $command = $this->denormalizer->denormalize(CreateUserCommand::class, $inputData);

        $this->validator->validate($command);

        $this->handler->run($command);

        return $this->respondWithData(null, 201);
    }
}
