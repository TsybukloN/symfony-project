<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class FirmwareFileStorage
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\File]
    private ?\MongoDB\BSON\Binary $fileData = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFileData(): ?\MongoDB\BSON\Binary
    {
        return $this->fileData;
    }

    public function setFileData(\MongoDB\BSON\Binary $fileData): self
    {
        $this->fileData = $fileData;
        return $this;
    }
}

