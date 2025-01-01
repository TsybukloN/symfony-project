<?php

namespace App\Service;

use App\Entity\Firmwares;
use App\Repository\FirmwaresRepository;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;

class FirmwareService
{
    private Connection $connection;
    private FirmwaresRepository $firmwareRepository;

    public function __construct(Connection $connection, FirmwaresRepository $firmwareRepository)
    {
        $this->connection = $connection;
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
        $firmware->setUploadedBy($request->getSession()->get('user') instanceof Users ? $request->getSession()->get('user') : null);

        $this->save($firmware);

        return true;
    }

    public function save(Firmwares $firmware): void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->insert('firmwares')
            ->values([
                'name' => ':name',
                'file_path' => ':file_path',
                'version' => ':version',
                'uploaded_at' => ':uploaded_at',
                'uploaded_by' => ':uploaded_by',
            ])
            ->setParameters([
                'name' => $firmware->getName(),
                'file_path' => $firmware->getFilePath(),
                'version' => $firmware->getVersion(),
                'uploaded_at' => $firmware->getUploadedAt()?->format('Y-m-d H:i:s'),
                'uploaded_by' => $firmware->getUploadedBy()?->getId(),
            ])
            ->executeStatement();
    }

    public function delete(Firmwares $firmware): void
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
    }

    public function getPaginatedFirmwares(int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        return $this->firmwareRepository->findBy([], [], $perPage, $offset);
    }

    public function getTotalFirmwareCount(): int
    {
        return $this->firmwareRepository->count([]);
    }
}
