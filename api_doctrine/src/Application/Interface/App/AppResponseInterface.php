<?php
declare(strict_types=1);

namespace PhpLab\Application\Interface\App;

use PhpLab\Application\Response\ResponsePayload;
use Psr\Http\Message\ResponseInterface as Response;

interface AppResponseInterface extends Response
{
    public function respond(ResponsePayload $payload = null): self;
}