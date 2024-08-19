<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Exception;

use PhpLab\Domain\DomainException\DomainException;

class IncorrectAuthNumberException extends DomainException
{
    public $code = 423;
    public $message = 'user.exception.incorrect.auth.number';
}
