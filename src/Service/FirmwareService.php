<?php

namespace App\Service;

use App\Entity\FirmwareFileStorage;
use App\Entity\Firmwares;
use App\Repository\FirmwaresRepository;
use App\Repository\ProjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;

class FirmwareService
{
    private EntityManagerInterface $entityManager;
    private ProjectRepository $projectRepository;
    private FirmwaresRepository $firmwareRepository;

    public function __construct(EntityManagerInterface $entityManager, FirmwaresRepository $firmwareRepository, ProjectRepository $projectRepository)
    {
        $this->entityManager = $entityManager;
        $this->firmwareRepository = $firmwareRepository;
        $this->projectRepository = $projectRepository;
    }

    public function handleAddFirmware(Request $request): bool
    {
        $projectId = $request->query->get('projectId');
        if (!$projectId || !filter_var($projectId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $project = $this->projectRepository->find($projectId);
        if (!$project) {
            return false;
        }

        $version = $request->request->get('version');
        if (!$version || trim($version) === '') {
            return false;
        }

        $file = $request->files->get('file');
        if (!$file) {
            return true;
        }

        try {
            $fileData = file_get_contents($file->getPathname());

            $fileStorage = new FirmwareFileStorage();

            $mimiType = null;
            if ($file->getMimeType()) {
                $mimiType = $file->getMimeType();
            }
            $fileStorage->setFile($fileData, $mimiType);

            $this->entityManager->persist($fileStorage);
            $this->entityManager->flush();

            $firmware = new Firmwares();
            $firmware->setVersion($version)
                ->setUploadedAt(new \DateTimeImmutable())
                ->setFirmwareFileId($fileStorage->getId());

            $this->entityManager->persist($firmware);
            $this->entityManager->flush();

            $project->addFirmwareId($firmware->getId());

            $this->entityManager->persist($project);
            $this->entityManager->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function handleDeleteFirmware(int $id): bool
    {
        if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
            return false;
        }

        $firmware = $this->firmwareRepository->find($id);
        if (!$firmware) {
            return false;
        }

        try {
            // Удаляем прошивку
            $this->entityManager->remove($firmware);
            $this->entityManager->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function handleEditFirmware(int $firmwareId, int $projectId, Request $request): bool
    {
        if (!$firmwareId || !filter_var($firmwareId, FILTER_VALIDATE_INT)) {
            return false;
        }

        if (!$projectId || !filter_var($projectId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $firmware = $this->firmwareRepository->find($firmwareId);

        if (!$firmware) {
            return false;
        }

        $version = $request->request->get('version');
        if (!$version || trim($version) === '') {
            return false;
        }

        $file = $request->files->get('file');
        if (!$file) {
            return false;
        }

        try {
            $fileData = file_get_contents($file->getPathname());

            $fileStorage = new FirmwareFileStorage();
            $fileStorage->setFile($fileData, $file->getMimeType());

            $this->entityManager->persist($fileStorage);
            $this->entityManager->flush();

            $firmware->setVersion($version)
                ->setUploadedAt(new \DateTimeImmutable())
                ->setFirmwareFileId($fileStorage->getId());

            $this->entityManager->persist($firmware);
            $this->entityManager->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
