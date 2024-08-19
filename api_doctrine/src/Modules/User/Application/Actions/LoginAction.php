<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;

use PhpLab\Application\Actions\Action;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use PhpLab\Application\Interface\Hydration\DenormalizerInterface;
use PhpLab\Application\Interface\Validator\ValidatorInterface;
use PhpLab\Modules\User\Domain\Command\LoginUser\LoginUserCommand;
use PhpLab\Modules\User\Domain\Command\LoginUser\LoginUserHandler;

use OpenApi\Attributes as OA;


#[OA\Post(
    path: '/admin/auth/login',
    description: "Login user on admin panel",
    security: [],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/AdminAuthLoginUserCommand')
    ),
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
                        ref: '#/components/schemas/AdminAuthLoginRespond'
                    ),
                ],
                type: 'object',
            )
        ),
        new OA\Response(ref: '#/components/responses/Error', response: 'Error'),
    ]
)]
final class LoginAction extends Action
{
    public function __construct(
        private readonly DenormalizerInterface $denormalizer,
        private readonly ValidatorInterface $validator,
        private readonly LoginUserHandler $handler
    ) {
    }

    protected function action(): Response
    {
        $inputData = (array) $this->request->getParsedBody();

        $command = $this->denormalizer->denormalize(LoginUserCommand::class, $inputData);

        $this->validator->validate($command);

        $respond = $this->handler->run($command);

        return $this->respondWithData($respond);
    }
}
