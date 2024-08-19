<?php
declare(strict_types=1);

namespace PhpLab\Application\Middleware;


use PhpLab\Application\Interface\App\AppRequestHandlerInterface;
use PhpLab\Modules\User\Domain\Exception\AccessDeniedException;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use PhpLab\Modules\User\Domain\Service\AccessTokenService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Log\LoggerInterface;

final readonly class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccessTokenService $accessTokenService,
        private LoggerInterface    $logger
    )
    {
    }

    public function process(
        Request $request,
        AppRequestHandlerInterface|RequestHandlerInterface $handler
    ): Response
    {
        $token = $this->getTokenFromRequest($request);

        $this->logger->debug('token header: ' . $token);

        if (!$token) {
            AccessDeniedException::create();
        }

        $payload = $this->accessTokenService->parseAccessToken($token);

        $request = $request->withAttribute('jwtPayload', $payload);

        return $handler->handle($request);
    }

    private function getTokenFromRequest(Request $request): ?string
    {
        $jwtHeader = $request->getHeaderLine('Authorization') ?? null;

        if ($jwtHeader && preg_match('/Bearer\s+(.*)$/', $jwtHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
