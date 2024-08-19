<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;

use PhpLab\Application\Actions\Action;
use PhpLab\Application\Validator\Validator;
use PhpLab\Modules\User\Domain\Query\ListUsers\ListUsersFetcher;
use PhpLab\Modules\User\Domain\Query\ListUsers\ListUsersQuery;
use Psr\Http\Message\ResponseInterface as Response;

final class ListUsersAction extends Action
{
    public function __construct(
        private readonly Validator $validator,
        private readonly ListUsersFetcher $fetcher
    )
    {
    }

    protected function action(): Response
    {
        $inputData = (array) $this->request->getQueryParams();

        $query = ListUsersQuery::create($inputData);

        $this->validator->validate($query->filter);

        $respond = $this->fetcher->fetch($query);

        return $this->respondWithData($respond);
    }
}
