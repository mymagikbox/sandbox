<?php
declare(strict_types=1);

namespace PhpLab\Application\Middleware;

use PhpLab\Application\Actions\ActionError;
use PhpLab\Application\Actions\ActionPayload;
use PhpLab\Application\Response\JsonResponse;
use PhpLab\Application\Validator\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\ConstraintViolationListInterface;
final class ValidationExceptionHandler implements Middleware
{
    public function __construct(
        private readonly Translator $translator,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $exception) {
            $error = new ActionError(
                ActionError::VALIDATION_ERROR,
                $this->translator->trans('exception.validation'),
                $this->errorsArray($exception->getViolations())
            );

            $payload = new ActionPayload(422, null, $error);

            return new JsonResponse($payload, $payload->getStatusCode());
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