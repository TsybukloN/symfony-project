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

    /*#[Route('/firmwares', name: 'firmwares_list')]
    public function list(): Response
    {
        $firmwares = $this->firmwareService->getPaginatedFirmwares(1);

        return $this->render('firmwares/list.html.twig', [
            'firmwares' => $firmwares,
        ]);
    }*/

    #[Route('/firmwares/add', name: 'firmwares_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $isAdded = $this->firmwareService->handleAddFirmware($request);

        if ($isAdded) {
            $this->addFlash('success', 'Firmware added successfully.');
            return $this->redirectToRoute('project_list');
        }

        $this->addFlash('error', 'There was an error adding the firmware.');

        return $this->render('firmwares/form.html.twig');
    }

    #[Route('/firmwares/delete/{id}', name: 'firmwares_delete')]
    public function delete(int $id): Response
    {
        $firmware = $this->firmwaresRepository->find($id);
        if ($firmware) {
            $this->firmwareService->delete($firmware);
            $this->addFlash('success', 'Firmware deleted successfully.');
        } else {
            $this->addFlash('error', 'Firmware not found.');
        }

        return $this->redirectToRoute('project_list');
    }

    #[Route('/firmwares/edit/{id}', name: 'firmwares_edit')]
    public function edit(int $id, Request $request): Response
    {
        $firmware = $this->firmwaresRepository->find($id);
        if (!$firmware) {
            $this->addFlash('error', 'Firmware not found.');
            return $this->redirectToRoute('project_list');
        }

        $updatedData = $request->request->all();
        $this->firmwareService->edit($firmware, $updatedData);

        $this->addFlash('success', 'Firmware updated successfully.');

        return $this->render('firmwares/form.html.twig');
    }
}
