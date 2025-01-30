<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FirmwareService;
use App\Repository\FirmwaresRepository;


class FirmwareController extends AbstractController
{
    private FirmwareService $firmwareService;
    private FirmwaresRepository $firmwaresRepository;

    public function __construct(FirmwareService $firmwareService, FirmwaresRepository $firmwaresRepository)
    {
        $this->firmwareService = $firmwareService;
        $this->firmwaresRepository = $firmwaresRepository;
    }

    #[Route('/firmwares/add', name: 'firmwares_add')]
    public function add(Request $request): Response
    {
        $projectId = $request->query->get('projectId');
        $projectId = (int) $projectId;
        if (!$projectId) {
            throw $this->createNotFoundException('Project ID is missing');
        }

        if ($request->isMethod('POST')) {
            $isAdded = $this->firmwareService->handleAddFirmware($request);

            if ($isAdded) {
                $this->addFlash('success', 'Firmware added successfully.');
                return $this->redirectToRoute('project_list');
            }
            else {
                return new Response('There was an error adding the firmware.', 400);
            }
        }

        $this->addFlash('error', 'There was an error adding the firmware.');

        return $this->render('firmwares/add.html.twig', [
            'projectId' => $projectId,
        ]);

    }

    #[Route('/firmwares/delete/{id}', name: 'firmwares_delete')]
    public function delete(int $id): Response
    {
        $firmware = $this->firmwaresRepository->find($id);
        if ($firmware) {
            $this->firmwareService->handleDeleteFirmware($firmware);
            $this->addFlash('success', 'Firmware deleted successfully.');
        } else {
            $this->addFlash('error', 'Firmware not found.');
        }

        return $this->redirectToRoute('project_list');
    }

    #[Route('/project/{projectId}/firmwares/edit/{id}', name: 'firmwares_edit')]
    public function edit(int $id, int $projectId, Request $request): Response
    {
        $firmware = $this->firmwaresRepository->find($id);

        if (!$firmware) {
            $this->addFlash('error', 'Firmware not found.');
            return $this->redirectToRoute('project_list');
        }

        if ($request->isMethod('POST')) {
            $idEdited = $this->firmwareService->handleEditFirmware($id, $projectId, $request);
            if (!$idEdited) {
                return new Response('There was an error adding the firmware.', 400);
            }
            $this->addFlash('success', 'Firmware updated successfully.');
            return $this->redirectToRoute('project_edit', ['id' => $projectId]);
        }

        return $this->render('firmwares/edit.html.twig', [
            'firmware' => $firmware,
            'projectId' => $projectId,
        ]);
    }

}
