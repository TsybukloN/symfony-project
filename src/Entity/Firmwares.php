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

    #[ORM\ManyToOne(targetEntity: Firmwares::class)]
    #[ORM\JoinColumn(name: "firmware_id", referencedColumnName: "id")]
    private ?Firmwares $firmware_id = null;

    #[ORM\Column(length: 255)]
    private ?string $file_path = null;

    #[ORM\Column(length: 50)]
    private ?string $version = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $uploaded_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirmwareId(): ?Firmwares
    {
        return $this->firmware_id;
    }

    public function setFirmwareId(?Firmwares $firmware_id): static
    {
        $this->firmware_id = $firmware_id;
        return $this;
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
}
