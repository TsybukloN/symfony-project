<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Devices::class)]
    #[ORM\JoinColumn(name: "device_id", referencedColumnName: "id")]
    private ?Devices $device_id = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "uploaded_by", referencedColumnName: "id")]
    private ?Users $uploadedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getDeviceId(): ?Devices
    {
        return $this->device_id;
    }

    public function setDeviceId(?Devices $device_id): static
    {
        $this->device_id = $device_id;

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
