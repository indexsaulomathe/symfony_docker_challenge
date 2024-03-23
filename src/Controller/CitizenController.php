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

            $this->addFlash('success', 'Cidadão cadastrado com sucesso!');

            return $this->redirectToRoute('app_new_citizen');
        }

        return $this->render('citizen_new/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}