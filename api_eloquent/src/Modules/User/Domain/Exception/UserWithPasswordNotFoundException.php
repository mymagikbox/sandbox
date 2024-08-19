<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Exception;

use PhpLab\Domain\DomainException\DomainException;

class UserWithPasswordNotFoundException extends DomainException
{
    public $code = 422;
    public $message = 'user.exception.username.or.password.invalid';
}
