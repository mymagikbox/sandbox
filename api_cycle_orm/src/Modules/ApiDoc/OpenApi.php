<?php
declare(strict_types=1);

namespace PhpLab\Modules\ApiDoc;

use OpenApi\Attributes as OA;


#[OA\OpenApi(
    info: new OA\Info(
        version: "0.1",
        description: "
Pre-conditions
==============
- All request are protected, authorization in header `Bearer`
- All requests with json data in body MUST have `Content-Type: application/json` header specified
- All dates passed to/from server should be in UTC time zone
- All dates should be formatted as YYYY-MM-DD Example: `2017-01-26`
        ",
        title: "API documentation"
    ),
    servers: [new OA\Server(url: '/', description: 'base path')],
    security: [
        ['bearerAuth' => []]
    ]
)]
#[OA\Components(
    securitySchemes: [
        new OA\SecurityScheme(
            securityScheme: 'bearerAuth',
            type: 'http',
            scheme: 'bearer'
        )
    ]
)]
class OpenApi
{

}