<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Users;
use App\Entity\Devices;
use App\Entity\Projects;
use App\Repository\ProjectRepository;


class ProjectService
{
    private EntityManagerInterface $entityManager;
    private ProjectRepository $projectRepository;

    public function __construct(EntityManagerInterface $entityManager, ProjectRepository $projectRepository)
    {
        $this->entityManager = $entityManager;
        $this->projectRepository = $projectRepository;
    }

    public function handleAddProject(Request $request, Users $user): bool
    {
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $device_id = $request->request->get('device_id');

        if (!$name || !$description || !$device_id) {
            return false;
        }

        $device = $this->entityManager->getRepository(Devices::class)->find($device_id);
        if (!$device) {
            return false;
        }

        $project = new Projects();
        $project->setName($name)
                ->setDescription($description)
                ->setDevice($device)
                ->setUploadedBy($user);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return true;
    }

    public function handleEditProject(int $id, Request $request): bool
    {
        $project = $this->entityManager->getRepository(Projects::class)->find($id);

        if (!$project) {
            return false;
        }

        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $device_id = $request->request->get('device_id');

        if ($name) {
            $project->setName($name);
        }
        if ($description) {
            $project->setDescription($description);
        }
        if ($device_id) {
            $device = $this->entityManager->getRepository(Devices::class)->find($device_id);
            if ($device) {
                $project->setDevice($device);
            } else {
                return false;
            }
        }

        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function handleDeleteProject(int $id): bool
    {
        $project = $this->entityManager->getRepository(Projects::class)->find($id);

        if (!$project) {
            return false;
        }

        try {
            $this->entityManager->remove($project);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getPaginatedProjects(
        string $search = '',
        array $deviceIds = [],
        ?Users $currentUser = null,
        bool $myProjects = false,
        int $page = 1,
        int $perPage = 50,
        string $sortField = 'name',
        string $direction = 'asc'
    ) {
        $offset = ($page - 1) * $perPage;

        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Projects::class, 'p')
            ->leftJoin('p.device', 'd')
            ->leftJoin('p.uploadedBy', 'u')
            ->addSelect('d', 'u');

        if (!empty($search)) {
            $queryBuilder->andWhere('p.name LIKE :search OR d.name LIKE :search OR u.username LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if (!empty($deviceIds)) {
            $queryBuilder->andWhere('d.id IN (:deviceIds)')
                ->setParameter('deviceIds', $deviceIds);
        }

        if ($myProjects && $currentUser !== null) {
            $queryBuilder->andWhere('u.id = :currentUserId')
                ->setParameter('currentUserId', $currentUser->getId());
        }

        switch ($sortField) {
            case 'device':
                $queryBuilder->orderBy('d.name', $direction);
                break;
            case 'uploadedBy':
                $queryBuilder->orderBy('u.username', $direction);
                break;
            default:
                $queryBuilder->orderBy('p.name', $direction);
                break;
        }

        $queryBuilder->setFirstResult($offset)
            ->setMaxResults($perPage);

        return $queryBuilder->getQuery()->getResult();
    }

    public function getTotalProjectsCount(
        string $search = '',
        array $deviceIds = [],
        ?Users $currentUser = null
    ): int {
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('COUNT(p.id)')
            ->from(Projects::class, 'p')
            ->leftJoin('p.device', 'd')
            ->leftJoin('p.uploadedBy', 'u');

        if (!empty($search)) {
            $queryBuilder->andWhere('p.name LIKE :search OR d.name LIKE :search OR u.username LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if (!empty($deviceIds)) {
            $queryBuilder->andWhere('d.id IN (:deviceIds)')
                ->setParameter('deviceIds', $deviceIds);
        }

        if ($currentUser !== null) {
            $queryBuilder->andWhere('u.id = :currentUserId')
                ->setParameter('currentUserId', $currentUser->getId());
        }

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
