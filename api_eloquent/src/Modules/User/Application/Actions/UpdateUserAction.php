<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;


use PhpLab\Application\Actions\Action;
use PhpLab\Application\Serializer\Denormalizer;
use PhpLab\Application\Validator\Validator;
use PhpLab\Modules\User\Domain\Command\UpdateUser\UpdateUserCommand;
use PhpLab\Modules\User\Domain\Command\UpdateUser\UpdateUserHandler;
use Psr\Http\Message\ResponseInterface as Response;

final class UpdateUserAction extends Action
{
    public function __construct(
        private readonly Denormalizer $denormalizer,
        private readonly Validator $validator,
        private readonly UpdateUserHandler $handler
    )
    {
    }

    protected function action(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $inputData = (array) $this->request->getParsedBody();

        $command = $this->denormalizer->denormalize($inputData, UpdateUserCommand::class);

        $this->validator->validate($command);

        $this->handler->run($userId, $command);

        return $this->respondWithData(null, 201);
    }
}
