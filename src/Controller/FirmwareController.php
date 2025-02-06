<?php

namespace App\Controller;

use App\Entity\Uploads;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FirmwareService;
use App\Repository\FirmwaresRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\FirmwareFileStorage;


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
                return $this->redirectToRoute('project_list');
            }
            else {
                return $this->redirectToRoute('error', ['statusCode' => 400, 'message' => 'There was an error adding the firmware.']);
            }
        }

        return $this->render('firmwares/add.html.twig', [
            'projectId' => $projectId,
        ]);
    }

    #[Route('/firmwares/download/{id}', name: 'firmwares_download')]
    public function download(int $id, EntityManagerInterface $em): Response
    {
        $firmware = $this->firmwaresRepository->find($id);

        if (!$firmware) {
            return $this->redirectToRoute('error', ['statusCode' => 400, 'message' => 'Firmware not found.']);
        }

        $fileStorage = $em->getRepository(FirmwareFileStorage::class)->find($firmware->getFirmwareFileId());

        if (!$fileStorage) {
            return new Response('File not found', Response::HTTP_NOT_FOUND);
        }

        $fileData = stream_get_contents($fileStorage->getFileData());
        $contentType = $fileStorage->getMimeType() ?? 'application/octet-stream';
        $fileName = 'firmware_' . $firmware->getVersion() . '.' . $fileStorage->getExtension();

        $upload = new Uploads();
        $upload->setFirmwareId($id)
            ->setUploadedAt(new \DateTimeImmutable())
            ->setUserId($this->getUser()->getId());

        $em->persist($upload);
        $em->flush();

        return new Response(
            $fileData,
            Response::HTTP_OK,
            [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Content-Length' => strlen($fileData),
            ]
        );
    }

    #[Route('/firmwares/delete/{id}', name: 'firmwares_delete')]
    public function delete(int $id): Response
    {
        $isDeleted =  $this->firmwareService->handleDeleteFirmware($id);
        if (!$isDeleted) {
            return $this->redirectToRoute('error', ['statusCode' => 400, 'message' => 'There was an error deleting the firmware.']);
        }

        return $this->redirectToRoute('project_list');
    }

    #[Route('/project/{projectId}/firmwares/edit/{id}', name: 'firmwares_edit')]
    public function edit(int $id, int $projectId, Request $request): Response
    {
        $firmware = $this->firmwaresRepository->find($id);

        if (!$firmware) {
            return $this->redirectToRoute('error', ['statusCode' => 400, 'message' => 'Firmware not found']);
        }

        if ($request->isMethod('POST')) {

            $idEdited = $this->firmwareService->handleEditFirmware($id, $projectId, $request);
            if (!$idEdited) {
                return $this->redirectToRoute('error', ['statusCode' => 400, 'message' => 'There was an error editing the firmware.']);
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
