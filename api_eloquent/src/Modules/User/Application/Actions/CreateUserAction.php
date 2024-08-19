<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;


use PhpLab\Application\Actions\Action;
use PhpLab\Application\Serializer\Denormalizer;
use PhpLab\Application\Validator\Validator;
use PhpLab\Modules\User\Domain\Command\CreateUser\CreateUserCommand;
use PhpLab\Modules\User\Domain\Command\CreateUser\CreateUserHandler;
use Psr\Http\Message\ResponseInterface as Response;

final class CreateUserAction extends Action
{
    public function __construct(
        private readonly Denormalizer $denormalizer,
        private readonly Validator $validator,
        private readonly CreateUserHandler $handler
    ) {
    }

    protected function action(): Response
    {
        $inputData = (array) $this->request->getParsedBody();

        $command = $this->denormalizer->denormalize($inputData, CreateUserCommand::class);

        $this->validator->validate($command);

        $this->handler->run($command);

        return $this->respondWithData(null, 201);
    }
}
