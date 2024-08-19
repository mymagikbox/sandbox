<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;

use PhpLab\Application\Actions\Action;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use PhpLab\Application\Interface\Validator\ValidatorInterface;
use PhpLab\Modules\User\Domain\Query\ListUsers\ListUsersFetcher;
use PhpLab\Modules\User\Domain\Query\ListUsers\ListUsersQuery;

final class ListUsersAction extends Action
{
    public function __construct(
        private readonly ValidatorInterface $validator,
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
