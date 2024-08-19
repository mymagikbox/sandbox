<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;


use PhpLab\Application\Actions\Action;
use PhpLab\Application\Validator\Validator;
use PhpLab\Modules\User\Domain\Command\DeleteUser\DeleteUserCommand;
use PhpLab\Modules\User\Domain\Command\DeleteUser\DeleteUserHandler;
use Psr\Http\Message\ResponseInterface as Response;

final class DeleteUserAction extends Action
{
    public function __construct(
        private readonly DeleteUserHandler $handler,
        private readonly Validator $validator
    )
    {
    }

    protected function action(): Response
    {
        $inputData = (array) $this->request->getQueryParams();

        $command = new DeleteUserCommand(
            $this->request->getAttribute('jwtPayload'),
            (isset($inputData['userId']) && $inputData['userId']) ? (int) $inputData['userId'] : 0
        );

        $this->validator->validate($command);

        $this->handler->run($command);

        return $this->respondWithData(null, 201);
    }
}
