<?php
declare(strict_types=1);

namespace PhpLab\Application\Interface\Validator;

use PhpLab\Domain\Exception\Validator\ValidationException;

interface ValidatorInterface
{
    /***
     * @param object $object
     * @return void
     * @throws ValidationException
     */
    public function validate(object $object): void;
}