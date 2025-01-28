<?php

namespace App\Service;

use App\Entity\Projects;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Users;
use App\Entity\Devices;


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

    public function getPaginatedProjects(int $page = 1, int $perPage = 50): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);

        $offset = ($page - 1) * $perPage;

        try {
            return $this->projectRepository->findBy([], ['id' => 'ASC'], $perPage, $offset);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getTotalProjectsCount(): int
    {
        return $this->projectRepository->count([]);
    }
}
