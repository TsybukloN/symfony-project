<?php

namespace App\Controller;

use App\Service\FirmwareService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(
        Request $request,
        FirmwareService $firmwareService
    ): Response {
        $page = max(1, (int) $request->query->get('page', 1));

        $firmwares = $firmwareService->getPaginatedFirmwares($page);

        if (empty($firmwares)) {
            return $this->render('dashboard/index.html.twig', [
                'firmware_count' => 0,
                'firmwares' => [],
                'current_page' => $page,
                'total_pages' => 1,
            ]);
        }

        $firmwareCount = $firmwareService->getTotalFirmwareCount();
        $perPage = 50;
        $totalPages = (int) ceil($firmwareCount / $perPage);

        return $this->render('dashboard/index.html.twig', [
            'firmware_count' => $firmwareCount,
            'firmwares' => $firmwares,
            'current_page' => $page,
            'total_pages' => $totalPages,
        ]);
    }
}
