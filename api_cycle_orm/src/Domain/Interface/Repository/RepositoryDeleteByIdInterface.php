<?php
declare(strict_types=1);

namespace PhpLab\Domain\Interface\Repository;

interface RepositoryDeleteByIdInterface
{
    /**
     * @param int $id
     */
    public function deleteById(int $id): void;
}