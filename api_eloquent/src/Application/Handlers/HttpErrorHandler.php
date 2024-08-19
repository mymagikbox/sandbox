<?php

declare(strict_types=1);

namespace PhpLab\Application\Handlers;

use PhpLab\Application\Actions\ActionError;
use PhpLab\Application\Actions\ActionPayload;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Slim\Interfaces\CallableResolverInterface;
use Symfony\Component\Translation\Translator;
use Throwable;

class HttpErrorHandler extends SlimErrorHandler
{
    protected $codeTypeList = [
        // Informational 1xx
        'STATUS_CONTINUE'=>100,
        'STATUS_SWITCHING_PROTOCOLS'=>101,
        'STATUS_PROCESSING'=>102,
        'STATUS_EARLY_HINTS'=>103,
        // Successful 2xx
        'STATUS_OK'=>200,
        'STATUS_CREATED'=>201,
        'STATUS_ACCEPTED'=>202,
        'STATUS_NON_AUTHORITATIVE_INFORMATION'=>203,
        'STATUS_NO_CONTENT'=>204,
        'STATUS_RESET_CONTENT'=>205,
        'STATUS_PARTIAL_CONTENT'=>206,
        'STATUS_MULTI_STATUS'=>207,
        'STATUS_ALREADY_REPORTED'=>208,
        'STATUS_IM_USED'=>226,
        // Redirection 3xx
        'STATUS_MULTIPLE_CHOICES'=>300,
        'STATUS_MOVED_PERMANENTLY'=>301,
        'STATUS_FOUND'=>302,
        'STATUS_SEE_OTHER'=>303,
        'STATUS_NOT_MODIFIED'=>304,
        'STATUS_USE_PROXY'=>305,
        'STATUS_RESERVED'=>306,
        'STATUS_TEMPORARY_REDIRECT'=>307,
        'STATUS_PERMANENT_REDIRECT'=>308,
        // Client Errors 4xx
        'STATUS_BAD_REQUEST'=>400,
        'STATUS_UNAUTHORIZED'=>401,
        'STATUS_PAYMENT_REQUIRED'=>402,
        'STATUS_FORBIDDEN'=>403,
        'STATUS_NOT_FOUND'=>404,
        'STATUS_METHOD_NOT_ALLOWED'=>405,
        'STATUS_NOT_ACCEPTABLE'=>406,
        'STATUS_PROXY_AUTHENTICATION_REQUIRED'=>407,
        'STATUS_REQUEST_TIMEOUT'=>408,
        'STATUS_CONFLICT'=>409,
        'STATUS_GONE'=>410,
        'STATUS_LENGTH_REQUIRED'=>411,
        'STATUS_PRECONDITION_FAILED'=>412,
        'STATUS_PAYLOAD_TOO_LARGE'=>413,
        'STATUS_URI_TOO_LONG'=>414,
        'STATUS_UNSUPPORTED_MEDIA_TYPE'=>415,
        'STATUS_RANGE_NOT_SATISFIABLE'=>416,
        'STATUS_EXPECTATION_FAILED'=>417,
        'STATUS_IM_A_TEAPOT'=>418,
        'STATUS_MISDIRECTED_REQUEST'=>421,
        'STATUS_UNPROCESSABLE_ENTITY'=>422,
        'STATUS_LOCKED'=>423,
        'STATUS_FAILED_DEPENDENCY'=>424,
        'STATUS_TOO_EARLY'=>425,
        'STATUS_UPGRADE_REQUIRED'=>426,
        'STATUS_PRECONDITION_REQUIRED'=>428,
        'STATUS_TOO_MANY_REQUESTS'=>429,
        'STATUS_REQUEST_HEADER_FIELDS_TOO_LARGE'=>431,
        'STATUS_UNAVAILABLE_FOR_LEGAL_REASONS'=>451,
        // Server Errors 5xx
        'STATUS_INTERNAL_SERVER_ERROR'=>500,
        'STATUS_NOT_IMPLEMENTED'=>501,
        'STATUS_BAD_GATEWAY'=>502,
        'STATUS_SERVICE_UNAVAILABLE'=>503,
        'STATUS_GATEWAY_TIMEOUT'=>504,
        'STATUS_VERSION_NOT_SUPPORTED'=>505,
        'STATUS_VARIANT_ALSO_NEGOTIATES'=>506,
        'STATUS_INSUFFICIENT_STORAGE'=>507,
        'STATUS_LOOP_DETECTED'=>508,
        'STATUS_NOT_EXTENDED'=>510,
        'STATUS_NETWORK_AUTHENTICATION_REQUIRED'=>511,
    ];

    private Translator $translator;

    public function __construct(
        Translator $translator,
        CallableResolverInterface $callableResolver,
        ResponseFactoryInterface $responseFactory,
        ?LoggerInterface $logger = null,
    )
    {
        $this->translator = $translator;

        parent::__construct($callableResolver, $responseFactory, $logger);
    }


    protected function respond(): Response
    {
        $exception = $this->exception;
        $statusCode = 500;
        $error = new ActionError(
            ActionError::SERVER_ERROR,
            'An internal error has occurred while processing your request.'
        );

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $error->setDescription($exception->getMessage());

            if ($exception instanceof HttpNotFoundException) {
                $error->setType(ActionError::RESOURCE_NOT_FOUND);
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $error->setType(ActionError::NOT_ALLOWED);
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $error->setType(ActionError::UNAUTHENTICATED);
            } elseif ($exception instanceof HttpForbiddenException) {
                $error->setType(ActionError::INSUFFICIENT_PRIVILEGES);
            } elseif ($exception instanceof HttpBadRequestException) {
                $error->setType(ActionError::BAD_REQUEST);
            } elseif ($exception instanceof HttpNotImplementedException) {
                $error->setType(ActionError::NOT_IMPLEMENTED);
            }
        }

        if (
            !($exception instanceof HttpException)
            && $exception instanceof Throwable
            && $this->displayErrorDetails
        ) {
            $error->setDescription($exception->getMessage());
            $statusCode = $exception->getCode() ?? $statusCode;

            $codeTypeList = array_flip($this->codeTypeList);
            if(isset($codeTypeList[$statusCode])) {
                $error->setType($codeTypeList[$statusCode]);
            }
        }

        $error->setDescription($this->translator->trans($error->getDescription()));

        $payload = new ActionPayload($statusCode, null, $error);
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
