<?php

namespace App\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Users;


class ProjectService
{
    private EntityManagerInterface $entityManager;
    private ProjectRepository $projectRepository;

    public function __construct(EntityManagerInterface $entityManager, ProjectRepository $projectRepository)
    {
        $this->entityManager = $entityManager;
        $this->projectRepository = $projectRepository;
    }

    public function handleAddProject(Request $request): bool
    {
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $device_id = $request->request->get('device_id');

        if (!$name || !$description || $device_id) {
            return false;
        }

        $project = new Project();
        $project->setName($name);
        $project->setDescription($description);
        $project->setDevice($device_id);
        $project->setUploadedBy($request->getSession()->get('user') instanceof Users ? $request->getSession()->get('user') : null);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return true;
    }

    public function handleEditProject(int $id, Request $request): bool
    {
        $project = $this->entityManager->getRepository(Project::class)->find($id);

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
            $project->setDeviceId($device_id);
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
        $project = $this->entityManager->getRepository(Project::class)->find($id);

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
        $offset = ($page - 1) * $perPage;
        return $this->projectRepository->findBy([], [], $perPage, $offset);
    }

    public function getTotalProjectsCount(): int
    {
        return $this->projectRepository->count([]);
    }
}
