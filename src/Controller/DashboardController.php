<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\FirmwaresRepository;
use App\Repository\UsersRepository;
use App\Repository\DevicesRepository;

class DashboardController extends AbstractController
{
    public function index(
        FirmwaresRepository $firmwareRepository,
        UsersRepository $userRepository,
        DevicesRepository $deviceRepository
    ): Response {
        $firmwares = $firmwareRepository->findBy([], ['uploadedAt' => 'DESC'], 5);
        return $this->render('dashboard/index.html.twig', [
            'firmware_count' => $firmwareRepository->count([]),
            'user_count' => $userRepository->count([]),
            'device_count' => $deviceRepository->count([]),
            'recent_firmwares' => $firmwares,
        ]);
    }
}
