<?php

namespace App\Entity;

use App\Repository\UploadsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UploadsRepository::class)]
class Uploads
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Firmwares::class)]
    #[ORM\JoinColumn(name: "firmware_id", referencedColumnName: "id")]
    private ?Firmwares $firmware_id = null;

    #[ORM\ManyToOne(targetEntity: Devices::class)]
    #[ORM\JoinColumn(name: "device_id", referencedColumnName: "id")]
    private ?Devices $device_id = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private ?Users $user_id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $uploaded_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirmwareId(): ?int
    {
        return $this->firmware_id;
    }

    public function setFirmwareId(int $firmware_id): static
    {
        $this->firmware_id = $firmware_id;

        return $this;
    }

    public function getDeviceId(): ?int
    {
        return $this->device_id;
    }

    public function setDeviceId(int $device_id): static
    {
        $this->device_id = $device_id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploaded_at;
    }

    public function setUploadedAt(\DateTimeImmutable $uploaded_at): static
    {
        $this->uploaded_at = $uploaded_at;

        return $this;
    }
}
