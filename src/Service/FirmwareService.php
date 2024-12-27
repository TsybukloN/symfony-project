<?php

namespace App\Service;

use App\Entity\Firmwares;
use App\Repository\FirmwaresRepository;
use Symfony\Component\HttpFoundation\Request;

class FirmwareService
{
    private FirmwaresRepository $firmwareRepository;

    public function __construct(FirmwaresRepository $firmwareRepository)
    {
        $this->firmwareRepository = $firmwareRepository;
    }

    public function handleAddFirmware(Request $request): bool
    {
        $name = $request->request->get('name');
        $filePath = $request->request->get('file_path');
        $version = $request->request->get('version');

        if (!$name || !$filePath || !$version) {
            return false;
        }

        $firmware = new Firmwares();
        $firmware->setName($name);
        $firmware->setFilePath($filePath);
        $firmware->setVersion($version);
        $firmware->setUploadedAt(new \DateTimeImmutable());

        $this->firmwareRepository->save($firmware, true);

        return true;
    }
}
