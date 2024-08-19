<?php
declare(strict_types=1);

namespace PhpLab\Application\Hydration;

use EventSauce\ObjectHydrator\ObjectMapperUsingReflection;
use PhpLab\Application\Interface\Hydration\DenormalizerInterface;

final class Denormalizer extends ObjectMapperUsingReflection implements DenormalizerInterface
{
    public function denormalize(string $className, array $payload): object
    {
        return $this->hydrateObject($className, $payload);
    }
}