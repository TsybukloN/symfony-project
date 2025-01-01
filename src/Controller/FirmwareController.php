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
    #[Route('/firmwares', name: 'firmwares_list')]
    public function list(FirmwareService $firmwareService): Response
    {
        $firmwares = $firmwareService->getPaginatedFirmwares(1);

        return $this->render('firmwares/list.html.twig', [
            'firmwares' => $firmwares,
        ]);
    }

    #[Route('/firmwares/add', name: 'firmwares_add')]
    public function add(Request $request, FirmwareService $firmwareService): Response
    {
        $isAdded = $firmwareService->handleAddFirmware($request);

        if ($isAdded) {
            $this->addFlash('success', 'Firmware added successfully.');
            return $this->redirectToRoute('firmwares_list');
        }

        $this->addFlash('error', 'There was an error adding the firmware.');

        return $this->render('firmwares/form.html.twig');
    }

    #[Route('/firmwares/delete/{id}', name: 'firmwares_delete')]
    public function delete(int $id, FirmwareService $firmwareService, FirmwaresRepository $repository): Response
    {
        $firmware = $repository->find($id);
        if ($firmware) {
            $firmwareService->delete($firmware);
            $this->addFlash('success', 'Firmware deleted successfully.');
        } else {
            $this->addFlash('error', 'Firmware not found.');
        }

        return $this->redirectToRoute('firmwares_list');
    }

    #[Route('/firmwares/edit/{id}', name: 'firmwares_edit')]
    public function edit(int $id, Request $request, FirmwareService $firmwareService, FirmwaresRepository $repository): Response
    {
        $firmware = $repository->find($id);
        if (!$firmware) {
            $this->addFlash('error', 'Firmware not found.');
            return $this->redirectToRoute('firmwares_list');
        }

        $updatedData = $request->request->all();
        $firmwareService->edit($firmware, $updatedData);

        $this->addFlash('success', 'Firmware updated successfully.');

        return $this->render('firmwares/form.html.twig');
    }
}
