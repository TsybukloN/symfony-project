<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\FirmwaresRepository;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProjectService;
use App\Repository\DevicesRepository;

class ProjectController extends AbstractController
{
    private ProjectService $projectService;
    private ProjectRepository $projectRepository;

    public function __construct(ProjectService $projectService, ProjectRepository $projectRepository)
    {
        $this->projectService = $projectService;
        $this->projectRepository = $projectRepository;
    }

    #[Route('/projects/{page<\d+>}', name: 'project_list')]
    public function list(Request $request, FirmwaresRepository $firmwaresRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('perPage', 50);

        $projects = $this->projectService->getPaginatedProjects($page, $perPage);
        $totalProjects = $this->projectService->getTotalProjectsCount();

        $pages = (int) ceil($totalProjects / $perPage);

        foreach ($projects as $project) {
            $firmwareIds = $project->getFirmwareIds();
            $firmwares = $firmwaresRepository->findBy(['id' => $firmwareIds]);

            $project->firmwares = $firmwares;
        }

        return $this->render('projects/list.html.twig', [
            'projects' => $projects,
            'page' => $page,
            'pages' => $pages,
        ]);
    }

    #[Route('/projects/add', name: 'project_add', methods: ['GET', 'POST'])]
    public function add(Request $request, DevicesRepository $deviceRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Users) {
            return $this->redirectToRoute('app_login');
        }

        $devices = $deviceRepository->findAll();

        $isAdded = $this->projectService->handleAddProject($request, $user);

        if ($isAdded) {
            $this->addFlash('success', 'Project added successfully.');
            return $this->redirectToRoute('project_list');
        }

        $this->addFlash('error', 'There was an error adding the project.');

        return $this->render('projects/form.html.twig', [
            'devices' => $devices,
        ]);
    }

    #[Route('/projects/edit/{id}', name: 'project_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, DevicesRepository $deviceRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Users) {
            return $this->redirectToRoute('app_login');
        }

        $devices = $deviceRepository->findAll();
        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Project not found.');
        }

        $isEdited = $this->projectService->handleEditProject($id, $request);

        if ($isEdited) {
            $this->addFlash('success', 'Project edited successfully.');

            return $this->render('projects/form.html.twig', [
                'devices' => $devices,
                'project' => $project,
            ]);
        }

        return $this->redirectToRoute('project_list');
    }

    #[Route('/projects/delete/{id}', name: 'project_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $isDeleted = $this->projectService->handleDeleteProject($id);

        if ($isDeleted) {
            $this->addFlash('success', 'Project deleted successfully.');
        } else {
            $this->addFlash('error', 'There was an error deleting the project.');
        }

        return $this->redirectToRoute('project_list');
    }
}
