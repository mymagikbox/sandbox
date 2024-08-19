<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Exception;

use PhpLab\Domain\DomainException\DomainException;

class InvalidTokenException extends DomainException
{
    public $code = 401;
    public $message = 'user.exception.invalid.token';
}
