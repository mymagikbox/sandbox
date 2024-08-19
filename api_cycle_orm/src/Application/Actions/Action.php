<?php
declare(strict_types=1);

namespace PhpLab\Application\Actions;

use Fig\Http\Message\StatusCodeInterface;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use PhpLab\Application\Response\ResponsePayload;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

abstract class Action
{
    protected Request $request;

    protected Response $response;

    protected array $args;

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        return $this->action();
    }

    abstract protected function action(): Response;

    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param array|object|null $data
     */
    protected function respondWithData(
        mixed $data = null,
        int $statusCode = StatusCodeInterface::STATUS_OK
    ): Response
    {
        $payload = new ResponsePayload($data, $statusCode);

        return $this->response->respond($payload);
    }
}