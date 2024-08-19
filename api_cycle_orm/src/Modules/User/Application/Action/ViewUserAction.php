<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Action;


use PhpLab\Application\Actions\Action;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use PhpLab\Modules\User\Domain\Query\ViewUsers\ViewUsersFetcher;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/admin/user/{id}/view',
    description: "view user info",
    security: [["bearerAuth" => []]],
    tags: ['user'],
    parameters: [
        new OA\Parameter(ref: '#/components/parameters/IdInPath'),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Success',
            content: new OA\JsonContent(
                required: ['success', 'data'],
                properties: [
                    new OA\Property(property:'success', type: 'boolean'),
                    new OA\Property(
                        property: 'data',
                        ref: '#/components/schemas/AdminUserViewRespond'
                    ),
                ],
                type: 'object',
            )
        ),
        new OA\Response(ref: '#/components/responses/Error', response: 'Error'),
    ]
)]
final class ViewUserAction extends Action
{
    public function __construct(
        private readonly ViewUsersFetcher $fetcher
    )
    {
    }

    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        $respond = $this->fetcher->fetch($userId);

        return $this->respondWithData($respond);
    }
}
