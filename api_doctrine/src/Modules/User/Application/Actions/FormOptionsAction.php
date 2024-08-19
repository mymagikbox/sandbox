<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Application\Actions;

use PhpLab\Application\Actions\Action;
use PhpLab\Application\Interface\App\AppResponseInterface as Response;
use PhpLab\Modules\User\Domain\Query\FormOptions\FormOptionsRespond;

final class FormOptionsAction extends Action
{
    protected function action(): Response
    {
        return $this->respondWithData(new FormOptionsRespond());
    }
}
