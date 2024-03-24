<?php

namespace App\Controller;

use App\Entity\Citizen;
use App\Form\CitizenType;
use App\Service\NisGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CitizenController extends AbstractController
{
    #[Route('/', name: 'app_new_citizen')]
    public function new(Request $request, EntityManagerInterface $entityManager, NisGeneratorService $nisGenerator): Response
    {
        $citizen = new Citizen();
        $form = $this->createForm(CitizenType::class, $citizen);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $citizen->setNis($nisGenerator->generateUniqueNIS());
            $entityManager->persist($citizen);
            $entityManager->flush();

            $this->addFlash('success', 'Cidadão cadastrado com sucesso! NIS: ' . $citizen->getNis());

            return $this->redirectToRoute('citizen_success', [
                'nis' => $citizen->getNis(),
                'name' => $citizen->getName(),
            ]);
        }

        return $this->render('citizen_new/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/citizen/success/{nis}/{name}', name: 'citizen_success')]
    public function success(string $nis, string $name): Response
    {
        return $this->render('citizen_success/success.html.twig', [
            'nis' => $nis,
            'name' => $name,
        ]);
    }
}
