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

    #[Route('/projects/{page<\d+>}', name: 'project_list', methods: ['GET'])]
    public function list(Request $request, DevicesRepository $deviceRepository, FirmwaresRepository $firmwaresRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('perPage', 50);

        $search = $request->query->get('search', '');
        $devices = $request->query->all('devices') ?: [];
        $myProjects = $request->query->getBoolean('myProjects', false);
        $sortField = $request->query->get('sort', 'name');
        $direction = $request->query->get('direction', 'asc');

        $allowedSortFields = ['name', 'device', 'uploadedBy'];
        $allowedDirections = ['asc', 'desc'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'name';
        }
        if (!in_array($direction, $allowedDirections)) {
            $direction = 'asc';
        }

        $currentUser = $this->getUser();
        if (!$currentUser instanceof Users) {
            return $this->redirectToRoute('app_login');
        }

        $projects = $this->projectService->getPaginatedProjects(
            $search,
            (array)$devices,
            $currentUser,
            $myProjects,
            $page,
            $perPage,
            $sortField,
            $direction
        );

        $totalProjects = $this->projectService->getTotalProjectsCount($search, (array)$devices, $currentUser);

        $pages = (int) ceil($totalProjects / $perPage);

        foreach ($projects as $project) {
            $firmwareIds = $project->getFirmwareIds();
            $firmwares = $firmwaresRepository->findBy(['id' => $firmwareIds]);
            $canEdit = $project->getUploadedBy()->getId() === $this->getUser()->getId();
            $project->canEdit = $canEdit;
            $project->firmwares = $firmwares;
        }

        $availableDevices = $deviceRepository->findAll();

        return $this->render('projects/list.html.twig', [
            'projects' => $projects,
            'page' => $page,
            'pages' => $pages,
            'search' => $search,
            'devices' => $devices,
            'availableDevices' => $availableDevices,
            'myProjects' => $myProjects,
            'sort' => $sortField,
            'direction' => $direction,
        ]);
    }

    #[Route('/projects/add', name: 'project_add')]
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

    #[Route('/projects/edit/{id}', name: 'project_edit')]
    public function edit(int $id, Request $request, DevicesRepository $deviceRepository , FirmwaresRepository $firmwaresRepository): Response
    {

        $user = $this->getUser();
        if (!$user instanceof Users) {
            return $this->redirectToRoute('app_login');
        }

        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Project not found.');
        }

        if ($request->isMethod('GET')) {
            $firmwareIds = $project->getFirmwareIds();
            $firmwares = $firmwaresRepository->findBy(['id' => $firmwareIds]);

            $project->firmwares = $firmwares;

            return $this->render('projects/form.html.twig', [
                'project' => $project,
                'devices' => $deviceRepository->findAll(),
            ]);
        }

        if ($request->isMethod('POST')) {
            $isEdited = $this->projectService->handleEditProject($id, $request);

            if ($isEdited) {
                $this->addFlash('success', 'Project updated successfully.');
                return $this->redirectToRoute('project_list');
            }
        }

        $this->addFlash('error', 'Failed to update project.');
        return $this->redirectToRoute('project_edit', ['id' => $id]);
    }

    #[Route('/projects/delete/{id}', name: 'project_delete')]
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
