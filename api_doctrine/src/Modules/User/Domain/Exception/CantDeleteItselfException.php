<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Exception;

use PhpLab\Domain\Exception\DomainException;

class CantDeleteItselfException extends DomainException
{
    const MESSAGE = 'user.exception.cant.delete.itself';
    public $message = self::MESSAGE;
    public $code = 422;
}
