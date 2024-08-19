<?php
declare(strict_types=1);

namespace PhpLab\Modules\ApiDoc;

use OpenApi\Attributes as OA;
use PhpLab\Domain\Query\Sorter;

#[OA\Parameter(
    parameter: 'IdInPath',
    name: 'id',
    in: 'path',
    required: true,
    schema: new OA\Schema(
        type: 'integer',
        format: 'int64',
        example: 1
    ),
    example: 1
)]
#[OA\Parameter(
    parameter: 'limit',
    name: 'limit',
    in: 'query',
    required: false,
    schema: new OA\Schema(
        type: 'integer',
        format: 'int32'
    ),
    example: 10
)]
#[OA\Parameter(
    parameter: 'page',
    name: 'page',
    in: 'query',
    required: false,
    schema: new OA\Schema(
        type: 'integer',
        format: 'int32'
    ),
    example: 1
)]
#[OA\Parameter(
    parameter: 'sortOrder',
    name: 'sort_order',
    in: 'query',
    required: false,
    schema: new OA\Schema(
        type: 'string',
        enum: [Sorter::SORT_ASC, Sorter::SORT_DESC],
    ),
    example: 'desc'
)]
final class OAParameters
{
 // common Parameters
}