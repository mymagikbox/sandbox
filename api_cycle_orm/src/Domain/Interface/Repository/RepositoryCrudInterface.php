<?php
declare(strict_types=1);

namespace PhpLab\Domain\Interface\Repository;

interface RepositoryCrudInterface extends RepositoryCreateInterface, RepositoryUpdateInterface, RepositoryDeleteInterface
{

}