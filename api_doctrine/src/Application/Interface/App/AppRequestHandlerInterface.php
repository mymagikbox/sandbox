<?php

namespace PhpLab\Application\Interface\App;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface AppRequestHandlerInterface extends RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): AppResponseInterface;
}