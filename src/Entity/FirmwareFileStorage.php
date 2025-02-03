<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FirmwareFileStorage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'binary')]
    private $fileData;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $mimeType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function getFileData()
    {
        return $this->fileData;
    }

    public function getExtension(): ?string
    {
        $mimeTypeToExtension = [
            // archives
            'application/octet-stream' => 'bin',
            'application/x-binary' => 'bin',
            'application/x-firmware' => 'fw',
            'application/x-msdownload' => 'exe',
            'application/x-dosexec' => 'exe',
            'application/x-gzip' => 'gz',
            'application/x-bzip2' => 'bz2',
            'application/zip' => 'zip',
            'application/x-7z-compressed' => '7z',
            'application/x-tar' => 'tar',

            // microcontroller firmware
            'application/x-intel-hex' => 'hex',
            'application/x-motorola-srecord' => 'srec',
            'application/x-elf' => 'elf',

            // other binary files
            'application/x-object' => 'o',
            'application/x-sharedlib' => 'so',
            'application/x-executable' => 'elf',
            'application/x-msdos-program' => 'com',

            // scripts
            'text/x-shellscript' => 'sh',
            'application/json' => 'json',
            'text/xml' => 'xml',
            'text/plain' => 'txt',
        ];

        return $mimeTypeToExtension[$this->mimeType] ?? null;
    }

    public function setFile($fileData, string $mimeType): self
    {
        $this->mimeType = $mimeType;
        $this->fileData = $fileData;
        return $this;
    }
}
