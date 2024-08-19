<?php
declare(strict_types=1);

namespace PhpLab\Application\Validator;

use LogicException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

final class ValidationException extends LogicException
{
    private ConstraintViolationListInterface $violations;

    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message = 'Invalid input.',
        int $code = 422,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->violations = $violations;
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