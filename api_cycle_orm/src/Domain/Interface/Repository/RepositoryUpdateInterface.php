<?php
declare(strict_types=1);

namespace PhpLab\Domain\Interface\Repository;

interface RepositoryUpdateInterface
{
    /**
     * @param object $entity
     */
    public function update(object $entity): void;
}