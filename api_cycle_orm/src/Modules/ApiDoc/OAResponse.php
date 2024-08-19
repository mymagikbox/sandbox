<?php
declare(strict_types=1);

namespace PhpLab\Modules\ApiDoc;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: "Error",
    description: "
                Errors:
                400 - Bad Request;
                401 - Wrong token provided;
                403 - Access denied;
                404 - Not found;
                409 - Other error;
                500 - Runtime error;
            ",
    content: new OA\JsonContent(
        required: ['success', 'error'],
        properties: [
            new OA\Property(property:'success', type: 'boolean'),
            new OA\Property(
                property: 'error',
                required: ['type', 'description'],
                properties: [
                    new OA\Property(
                        property: 'type',
                        type: 'string',
                        enum: ['validation', 'exception'],
                        example: 'validation',
                    ),
                    new OA\Property(property:'description', type: 'string'),
                    new OA\Property(
                        property: 'details',
                        type: 'object',
                        example: 'field_name: description'
                    ),
                ],
                type: 'object')
        ],
        type: 'object',
    )
)]
final class OAResponse
{
 // common Response
}