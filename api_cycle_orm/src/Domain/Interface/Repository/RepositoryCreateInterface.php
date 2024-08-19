<?php
declare(strict_types=1);

namespace PhpLab\Domain\Interface\Repository;

interface RepositoryCreateInterface
{
    /**
     * @param object $entity
     */
    public function create(object $entity): void;
}