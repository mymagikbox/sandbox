<?php
declare(strict_types=1);

namespace PhpLab\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Translator;

final class I18nMiddleware implements Middleware
{
    public function __construct(
        private readonly Translator $translator,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $locale = $request->getHeaderLine('X-Locale') ?? null;

        if (
            $locale &&
            $this->translator->getLocale() !== $locale
        ) {
            $this->logger->debug('Setup locale from header: '.$locale);
            $this->translator->setLocale($locale);
        }

        return $handler->handle($request);
    }
}
