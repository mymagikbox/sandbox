<?php
declare(strict_types=1);

namespace PhpLab\Application\Middleware;

use DomainException;
use Fig\Http\Message\StatusCodeInterface;
use InvalidArgumentException;
use PhpLab\Application\Interface\App\AppRequestHandlerInterface;
use PhpLab\Application\Interface\App\AppResponseFactoryInterface;
use PhpLab\Application\Interface\App\AppResponseInterface;
use PhpLab\Application\Response\ResponseError;
use PhpLab\Application\Response\ResponsePayload;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpException;
use Throwable;
use Symfony\Component\Translation\Translator;

final readonly class ExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Translator $translator,
        private AppResponseFactoryInterface $responseFactory,
        private ?LoggerInterface            $logger = null,
        private bool                        $displayErrorDetails = false,
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        AppRequestHandlerInterface|RequestHandlerInterface $handler
    ): AppResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            return $this->render($exception, $request);
        }
    }

    private function render(
        Throwable $exception,
        ServerRequestInterface $request,
    ): AppResponseInterface
    {
        $httpStatusCode = $this->getHttpStatusCode($exception);
        $response = $this->responseFactory->createResponse($httpStatusCode);

        // Log error
        if (isset($this->logger)) {
            $this->logger->error(
                sprintf(
                    '%s;Code %s;File: %s;Line: %s',
                    $exception->getMessage(),
                    $exception->getCode(),
                    $exception->getFile(),
                    $exception->getLine()
                ),
                $exception->getTrace()
            );
        }

        $error = new ResponseError(
            'exception',
            $this->translator->trans($exception->getMessage())
        );

        if ($this->displayErrorDetails) {
            $error->setDetails(
                [
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => (string) $exception->getLine(),
                    'trace' => $exception->getTrace(),
                ]
            );
        }

        $payload = new ResponsePayload(null, $httpStatusCode, $error);

        return $response->respond($payload);
    }

    private function getHttpStatusCode(Throwable $exception): int
    {
        $statusCode = StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
        }

        if ($exception instanceof DomainException || $exception instanceof InvalidArgumentException) {
            $statusCode = StatusCodeInterface::STATUS_BAD_REQUEST;
        }

        return $statusCode;
    }
}