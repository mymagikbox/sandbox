<?php
declare(strict_types=1);

namespace PhpLab\Application\Interface\Hydration;

interface DenormalizerInterface
{
    public function denormalize(string $className, array $payload): object;
}