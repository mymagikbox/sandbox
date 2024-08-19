<?php
declare(strict_types=1);

use PhpLab\Application\Hydration\Denormalizer;
use PhpLab\Application\Interface\Hydration\DenormalizerInterface;
use Psr\Container\ContainerInterface;

return [
    DenormalizerInterface::class => DI\get(Denormalizer::class),
];