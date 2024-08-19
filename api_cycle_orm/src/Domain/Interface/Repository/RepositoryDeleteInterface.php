<?php
declare(strict_types=1);

namespace PhpLab\Domain\Interface\Repository;

interface RepositoryDeleteInterface
{
    public function delete(object $entity): void;
}