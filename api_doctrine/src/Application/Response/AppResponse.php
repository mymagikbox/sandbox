<?php
declare(strict_types=1);

namespace PhpLab\Application\Response;

use PhpLab\Application\Interface\App\AppResponseInterface;
use Slim\Psr7\Response;

final class AppResponse extends Response implements AppResponseInterface
{
    public function respond(ResponsePayload $payload = null): self
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->getBody()->write($json);

        return $this
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($payload->getStatusCode());
    }
}