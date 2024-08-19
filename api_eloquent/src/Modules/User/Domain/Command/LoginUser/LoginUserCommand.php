<?php
declare(strict_types=1);

namespace PhpLab\Modules\User\Domain\Command\LoginUser;

use Symfony\Component\Validator\Constraints as Assert;

final class LoginUserCommand
{
    public function __construct(
        #[Assert\Length(min:3, max: 255)]
        #[Assert\NotBlank]
        public readonly string $username = '',
        #[Assert\Length(min: 6, max: 255)]
        #[Assert\NotBlank]
        public readonly string $password = ''
    ) {
    }
}