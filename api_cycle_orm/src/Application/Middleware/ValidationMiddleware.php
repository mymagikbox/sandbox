<?php
declare(strict_types=1);

namespace PhpLab\Application\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use PhpLab\Application\Interface\App\AppRequestHandlerInterface;
use PhpLab\Application\Interface\App\AppResponseFactoryInterface;
use PhpLab\Application\Interface\App\AppResponseInterface;
use PhpLab\Application\Response\ResponseError;
use PhpLab\Application\Response\ResponsePayload;
use PhpLab\Domain\Exception\Validator\ValidationException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final readonly class ValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Translator $translator,
        private AppResponseFactoryInterface $responseFactory
    )
    {
    }

    public function process(
        ServerRequestInterface $request,
        AppRequestHandlerInterface|RequestHandlerInterface $handler
    ): AppResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $exception) {
            $response = $this->responseFactory->createResponse();

            $error = new ResponseError(
                'validation',
                $this->translator->trans('exception.validation'),
                $this->errorsArray($exception->getViolations())
            );

            $payload = new ResponsePayload(null, StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $error);

            return $response->respond($payload);
        }
    }

    private function errorsArray(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $this->translator->trans($violation->getMessage());
        }
        return $errors;
    }
}