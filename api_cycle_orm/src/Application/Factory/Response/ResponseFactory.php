<?php
declare(strict_types=1);

namespace PhpLab\Application\Factory\Response;

use PhpLab\Application\Interface\App\AppResponseFactoryInterface;
use PhpLab\Application\Interface\App\AppResponseInterface;
use PhpLab\Application\Response\AppResponse;

class ResponseFactory implements AppResponseFactoryInterface
{
    public function createResponse(
        int $code = 200,
        string $reasonPhrase = ''
    ): AppResponseInterface
    {
        $res = new AppResponse($code);

        if ($reasonPhrase !== '') {
            $res = $res->withStatus($code, $reasonPhrase);
        }

        return $res;
    }
}