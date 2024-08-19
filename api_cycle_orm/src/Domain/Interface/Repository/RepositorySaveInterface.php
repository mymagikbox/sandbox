<?php
declare(strict_types=1);

namespace PhpLab\Domain\Interface\Repository;

interface RepositorySaveInterface
{
    public function addToSave(object $entity): void;
    public function save(): void;
}