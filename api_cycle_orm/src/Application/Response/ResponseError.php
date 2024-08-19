<?php
declare(strict_types=1);

namespace PhpLab\Application\Response;

use JsonSerializable;

final class ResponseError implements JsonSerializable
{
    public function __construct(
        private string $type,
        private ?string $description = null,
        private array $details = []
    )
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDetails(array $details = []): self
    {
        $this->details = $details;
        return $this;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function setDescription(?string $description = null): self
    {
        $this->description = $description;
        return $this;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'description' => $this->description,
            'details' => $this->details ?? '',
        ];
    }
}