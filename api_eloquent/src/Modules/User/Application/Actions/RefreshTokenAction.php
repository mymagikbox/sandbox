<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;

use PhpLab\Application\Actions\Action;
use PhpLab\Modules\User\Domain\Command\RefreshToken\RefreshTokenCommand;
use PhpLab\Modules\User\Domain\Command\RefreshToken\RefreshTokenHandler;
use Psr\Http\Message\ResponseInterface as Response;

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
