<?php

namespace App\Service;

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
        $version = $request->request->get('version');

        if ($version === null) {
            return false;
        }

        $firmware = new Firmwares();
        $firmware->setVersion($version)
            ->setUploadedAt(new \DateTimeImmutable())
            ->setFirmwareFileId(-1);

        $this->entityManager->persist($firmware);
        $this->entityManager->flush();

        # TODO : Add to project

        return true;
    }

    /*public function delete(Firmwares $firmware): void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->delete('firmwares')
            ->where('id = :id')
            ->setParameter('id', $firmware->getId())
            ->executeStatement();
    }

    public function edit(Firmwares $firmware, array $updatedData): void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->update('firmwares')
            ->set('name', ':name')
            ->set('file_path', ':file_path')
            ->set('version', ':version')
            ->set('uploaded_at', ':uploaded_at')
            ->where('id = :id')
            ->setParameters([
                'name' => $updatedData['name'] ?? $firmware->getName(),
                'file_path' => $updatedData['file_path'] ?? $firmware->getFilePath(),
                'version' => $updatedData['version'] ?? $firmware->getVersion(),
                'uploaded_at' => $updatedData['uploaded_at'] ?? $firmware->getUploadedAt()?->format('Y-m-d H:i:s'),
                'id' => $firmware->getId(),
            ])
            ->executeStatement();
    }*/
}
