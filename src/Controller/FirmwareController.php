<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Firmwares;
use App\Service\FirmwareService;
use App\Repository\FirmwaresRepository;

class FirmwareController extends AbstractController
{
    #[Route('/firmwares', name: 'firmwares_list')]
    public function list(FirmwaresRepository $repository): Response
    {
        return $this->render('firmwares/list.html.twig', [
            'firmwares' => $repository->findAll(),
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
}
