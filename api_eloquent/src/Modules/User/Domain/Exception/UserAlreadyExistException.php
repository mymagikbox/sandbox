<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Exception;

use PhpLab\Domain\DomainException\DomainException;

class UserAlreadyExistException extends DomainException
{
    const MESSAGE = 'user.exception.already.exist';

    public $message = self::MESSAGE;
    public $code = 422;
}
