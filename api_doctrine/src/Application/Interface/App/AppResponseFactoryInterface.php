<?php
declare(strict_types=1);

namespace PhpLab\Application\Interface\App;

use Psr\Http\Message\ResponseFactoryInterface;

interface AppResponseFactoryInterface extends ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): AppResponseInterface;
}