<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\SVGSymbolRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'svg_symbol')]
#[ORM\Entity(repositoryClass: SVGSymbolRepository::class)]
#[ApiResource(shortName: 'svg_symbols', operations: [new GetCollection(), new Post(), new Put(), new Patch(), new Delete()])]
class SVGSymbol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $data = null;

    #[ORM\Column]
    private ?float $width = null;

    #[ORM\Column]
    private ?float $height = null;

    #[ORM\Column]
    private ?float $pathLength = null;

    #[ORM\Column]
    private ?int $holes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getPathLength(): ?float
    {
        return $this->pathLength;
    }

    public function setPathLength(float $pathLength): static
    {
        $this->pathLength = $pathLength;

        return $this;
    }

    public function getHoles(): ?int
    {
        return $this->holes;
    }

    public function setHoles(int $holes): static
    {
        $this->holes = $holes;

        return $this;
    }
}
