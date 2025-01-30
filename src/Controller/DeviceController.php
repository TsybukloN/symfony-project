<?php

namespace App\Controller;

use App\Entity\Devices;
use App\Form\DeviceType;
use App\Repository\DevicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeviceController extends AbstractController
{
    /**
     * @Route("/devices", name="device_list", methods={"GET"})
     */
    public function list(DevicesRepository $deviceRepository): Response
    {
        $devices = $deviceRepository->findAll();

        return $this->render('device/list.html.twig', [
            'devices' => $devices,
        ]);
    }

    /**
     * @Route("/devices/add", name="device_add", methods={"GET", "POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $device = new Devices();
        $form = $this->createForm(DeviceType::class, $device);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($device);
            $entityManager->flush();

            $this->addFlash('success', 'Device added successfully.');

            return $this->redirectToRoute('device_list');
        }

        return $this->render('device/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/devices/edit/{id}", name="device_edit", methods={"GET", "POST"})
     */
    public function edit(
        Devices $device,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(DeviceType::class, $device);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Device updated successfully.');

            return $this->redirectToRoute('device_list');
        }

        return $this->render('device/edit.html.twig', [
            'form' => $form->createView(),
            'device' => $device,
        ]);
    }

    /**
     * @Route("/devices/delete/{id}", name="device_delete", methods={"POST"})
     */
    public function delete(
        Devices $device,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $device->getId(), $request->request->get('_token'))) {
            $entityManager->remove($device);
            $entityManager->flush();

            $this->addFlash('success', 'Device deleted successfully.');
        }

        return $this->redirectToRoute('device_list');
    }
}
