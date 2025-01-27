<?php

// src/Controller/ProjectController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProjectService;
use App\Repository\DevicesRepository;
use App\Entity\Projects;

class ProjectController extends AbstractController
{
    private ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    #[Route('/projects/{page<\d+>}', name: 'project_list')]
    public function list(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('perPage', 50);

        // Get paginated projects and total project count
        $projects = $this->projectService->getPaginatedProjects($page, $perPage);
        $totalProjects = $this->projectService->getTotalProjectsCount();

        // Calculate number of pages
        $pages = (int) ceil($totalProjects / $perPage);

        return $this->render('projects/list.html.twig', [
            'projects' => $projects,
            'page' => $page,
            'pages' => $pages,
        ]);
    }

    #[Route('/projects/add', name: 'project_add')]
    public function add(Request $request, DevicesRepository $deviceRepository): Response
    {
        $devices = $deviceRepository->findAll();

        $isAdded = $this->projectService->handleAddProject($request);

        if ($isAdded) {
            $this->addFlash('success', 'Project added successfully.');
            return $this->redirectToRoute('project_list');
        }

        $this->addFlash('error', 'There was an error adding the project.');

        return $this->render('projects/form.html.twig', [
            'devices' => $devices,
        ]);
    }

    #[Route('/projects/delete/{id}', name: 'project_delete')]
    public function delete(int $id, ProjectService $projectService): Response
    {
        $isDeleted = $projectService->handleDeleteProject($id);

        if ($isDeleted) {
            $this->addFlash('success', 'Project deleted successfully.');
            return $this->redirectToRoute('project_list');
        }

        $this->addFlash('error', 'There was an error deleting the project.');

        return $this->redirectToRoute('project_list');
    }

    #[Route('/projects/edit/{id}', name: 'project_edit')]
    public function edit(int $id, Request $request, ProjectService $projectService): Response
    {
        $isDeleted = $projectService->handleEditProject($id, $request);

        if ($isDeleted) {
            $this->addFlash('success', 'Project edited successfully.');
            return $this->redirectToRoute('project_list');
        }

        $this->addFlash('error', 'There was an error editing the project.');

        return $this->redirectToRoute('project_list');
    }
}
