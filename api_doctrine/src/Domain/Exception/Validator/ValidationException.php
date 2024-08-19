<?php
declare(strict_types=1);

namespace PhpLab\Domain\Exception\Validator;

use LogicException;
use PhpLab\Domain\Exception\DomainException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends DomainException
{
    public function __construct(
        private ConstraintViolationListInterface $violations,
        string $message = 'Invalid input.',
        int $code = 422,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public static function create(string $attribute, string $message)
    {
        throw new self(
            new ConstraintViolationList([
                new ConstraintViolation($message,'', [], null, $attribute, null)
            ])
        );
    }
}