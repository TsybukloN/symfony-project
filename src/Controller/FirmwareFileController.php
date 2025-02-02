<?php

// src/Controller/FileController.php

namespace App\Controller;

use App\Document\FirmwareFileStorage;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class FirmwareFileController extends AbstractController
{
    /**
     * @throws MongoDBException
     * @throws Throwable
     */
    #[Route('/upload', name: 'file_upload')]
    public function upload(Request $request, DocumentManager $dm): Response
    {
        if ($request->isMethod('POST') && $request->files->get('file')) {
            /** @var SymfonyUploadedFile $file */
            $file = $request->files->get('file');

            $fileData = new \MongoDB\BSON\Binary(file_get_contents($file->getPathname()), \MongoDB\BSON\Binary::TYPE_GENERIC);

            $firmwareFileStorage = new FirmwareFileStorage();
            $firmwareFileStorage->setFileData($fileData);

            $dm->persist($firmwareFileStorage);
            $dm->flush();

            return new Response('File uploaded successfully to MongoDB');
        }

        return $this->render('firmwares/upload.html.twig');
    }

    /**
     * @throws MappingException
     * @throws LockException
     */
    #[Route('/download/{id}', name: 'file_download')]
    public function download(string $id, DocumentManager $dm): Response
    {
        $firmwareFileStorage = $dm->getRepository(FirmwareFileStorage::class)->find($id);

        if (!$firmwareFileStorage) {
            throw $this->createNotFoundException('File not found');
        }

        $fileData = $firmwareFileStorage->getFileData();

        $response = new Response($fileData->getData());
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="file.bin"'); // Можно указать имя файла

        return $response;
    }
}


