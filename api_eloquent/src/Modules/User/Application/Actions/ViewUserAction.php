<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;


use PhpLab\Application\Actions\Action;
use PhpLab\Modules\User\Domain\Query\ViewUsers\ViewUsersFetcher;
use Psr\Http\Message\ResponseInterface as Response;

final class ViewUserAction extends Action
{
    public function __construct(
        private readonly ViewUsersFetcher $fetcher
    )
    {
    }

    protected function action(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $respond = $this->fetcher->fetch($userId);

        return $this->respondWithData($respond);
    }
}
