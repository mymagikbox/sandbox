<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;

use PhpLab\Application\Actions\Action;
use PhpLab\Modules\User\Domain\Query\FormOptions\FormOptionsRespond;
use Psr\Http\Message\ResponseInterface as Response;

final class FormOptionsAction extends Action
{
    protected function action(): Response
    {
        return $this->respondWithData(new FormOptionsRespond());
    }
}
