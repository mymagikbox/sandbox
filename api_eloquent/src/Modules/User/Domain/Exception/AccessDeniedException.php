<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Exception;

use PhpLab\Domain\DomainException\DomainException;

class AccessDeniedException extends DomainException
{
    const MESSAGE = 'user.exception.missing.token';
    const CODE_ACCESS_DENIED = 403;
    const CODE_TOKEN_FAIL = 401;

    public $code = self::CODE_ACCESS_DENIED;
    public $message = self::MESSAGE;

    public static function create()
    {
        throw new static();
    }

    public static function tokenFail()
    {
        throw new static(self::MESSAGE, self::CODE_TOKEN_FAIL);
    }
}
