<?php

namespace PhpLab\Domain;

final class Seo
{
    private string $title;
    private ?string $keywords;
    private ?string $description;

    public static function create(
        string $title,
        string $keywords = null,
        string $description = null
    ): self
    {
        $field = new self();
        $field
            ->setTitle($title)
            ->setKeywords($keywords)
            ->setDescription($description);

        return $field;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): static
    {
        $this->keywords = $keywords;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }
}