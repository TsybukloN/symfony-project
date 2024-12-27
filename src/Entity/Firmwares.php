<?php

namespace App\Entity;

use App\Repository\FirmwaresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FirmwaresRepository::class)]
class Firmwares
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $file_path = null;

    #[ORM\Column(length: 50)]
    private ?string $version = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $uploaded_at = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "uploaded_by", referencedColumnName: "id")]
    private ?Users $uploadedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getname(): ?string
    {
        return $this->name;
    }

    public function setname(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->file_path;
    }

    public function setFilePath(string $file_path): static
    {
        $this->file_path = $file_path;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploaded_at;
    }

    public function setUploadedAt(?\DateTimeImmutable $uploaded_at): static
    {
        $this->uploaded_at = $uploaded_at;

        return $this;
    }

    public function getUploadedBy(): ?Users
    {
        return $this->uploadedBy;
    }

    public function setUploadedBy(?Users $uploadedBy): static
    {
        $this->uploadedBy = $uploadedBy;
        return $this;
    }
}
