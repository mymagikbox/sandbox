<?php
declare(strict_types=1);

namespace PhpLab\Application\Middleware;


use PhpLab\Modules\User\Domain\Exception\AccessDeniedException;
use PhpLab\Modules\User\Domain\Services\AccessTokenService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Log\LoggerInterface;

final class RefreshTokenMiddleware implements Middleware
{
    public function __construct(
        private readonly AccessTokenService $accessTokenService,
        private readonly LoggerInterface    $logger
    )
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $token = $this->getTokenFromRequest($request);

        $this->logger->debug('refresh token header: ' . $token);

        if (!$token) {
            throw new AccessDeniedException();
        }

        $payload = $this->accessTokenService->parseRefreshToken($token);

        if(!$payload) {
            throw new AccessDeniedException();
        }

        $request = $request->withAttribute('refreshToken', $token);
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
