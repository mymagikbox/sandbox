<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;

use PhpLab\Application\Actions\Action;
use PhpLab\Modules\User\Domain\Command\LogoutUser\LogoutUserCommand;
use PhpLab\Modules\User\Domain\Command\LogoutUser\LogoutUserHandler;
use Psr\Http\Message\ResponseInterface as Response;

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
