<?php
declare(strict_types=1);

namespace PhpLab\Application\Validator;

use PhpLab\Application\Interface\Validator\ValidatorInterface;
use PhpLab\Domain\Exception\Validator\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;


final readonly class Validator implements ValidatorInterface
{
    public function __construct(
        private SymfonyValidatorInterface $validator
    )
    {
    }

    public function validate(object $object): void
    {
        $violations = $this->validator->validate($object);
        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }
    }
}