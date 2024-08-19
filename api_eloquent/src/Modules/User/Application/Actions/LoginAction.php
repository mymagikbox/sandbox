<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;

use PhpLab\Application\Actions\Action;
use PhpLab\Application\Serializer\Denormalizer;
use PhpLab\Application\Validator\Validator;
use PhpLab\Modules\User\Domain\Command\LoginUser\LoginUserCommand;
use PhpLab\Modules\User\Domain\Command\LoginUser\LoginUserHandler;
use Psr\Http\Message\ResponseInterface as Response;

final class LoginAction extends Action
{
    public function __construct(
        private readonly Denormalizer $denormalizer,
        private readonly Validator $validator,
        private readonly LoginUserHandler $handler
    ) {
    }

    protected function action(): Response
    {
        $inputData = (array) $this->request->getParsedBody();

        $command = $this->denormalizer->denormalize($inputData, LoginUserCommand::class);

        $this->validator->validate($command);

        $respond = $this->handler->run($command);

        return $this->respondWithData($respond);
    }
}
