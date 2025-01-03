<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProjectService;
use App\Repository\DevicesRepository;


class ProjectController extends AbstractController
{
    #[Route('/projects', name: 'project_list')]
    public function list(ProjectService $projectService): Response
    {
        $projects = $projectService->getPaginatedProjects(1);

        return $this->render('projects/list.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/projects/add', name: 'project_add')]
    public function add(Request $request, ProjectService $projectService, DevicesRepository $deviceRepository): Response
    {
        $devices = $deviceRepository->findAllDevices();

        $isAdded = $projectService->handleAddProject($request);

        if ($isAdded) {
            $this->addFlash('success', 'Firmware added successfully.');
            return $this->redirectToRoute('project_list');
        }

        $this->addFlash('error', 'There was an error adding the firmware.');

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
            return $this->redirectToRoute('projects_list');
        }

        $this->addFlash('error', 'There was an error deleting the project.');

        return $this->redirectToRoute('project_list');
    }

    #[Route('/projects/edit/{id}', name: 'project_edit')]
    public function edit(int $id, Request $request, ProjectService $projectService): Response
    {
        $isEdited = $projectService->handleEditProject($id, $request);

        if ($isEdited) {
            $this->addFlash('success', 'Project edited successfully.');
            return $this->redirectToRoute('project_list');
        }

        $this->addFlash('error', 'There was an error editing the project.');

        return $this->render('projects/form.html.twig');
    }
}
