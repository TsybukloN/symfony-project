<?php

namespace App\Entity;

use App\Repository\FirmwaresRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FirmwaresRepository::class)]
class Firmwares
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "integer")]
    private ?int $firmware_file_id = null;

    #[ORM\Column(type: "json", nullable: true)]
    private array $media_file_ids = [];

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^\d+\.\d+\.\d+$/", message: "Invalid version format.")]
    private ?string $version = null;

    #[ORM\Column()]
    private ?\DateTimeImmutable $uploaded_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirmwareFileId(): ?int
    {
        return $this->firmware_file_id;  // Исправлено
    }

    public function setFirmwareFileId(?int $firmware_file_id): static
    {
        $this->firmware_file_id = $firmware_file_id;  // Исправлено
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

    public function getMediaFileIds(): array
    {
        return $this->media_file_ids;
    }

    public function setMediaFileIds(array $media_file_ids): static
    {
        $this->media_file_ids = $media_file_ids;
        return $this;
    }
}
