<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CitizenRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CitizenSearchController extends AbstractController
{
    public function index(Request $request, CitizenRepository $citizenRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('nis', TextType::class, ['label' => 'NIS'])
            ->add('search', SubmitType::class, ['label' => 'Buscar'])
            ->getForm();

        $form->handleRequest($request);

        $citizen = null;
        $isCitizenFound = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $nis = $data['nis'];
            $citizen = $citizenRepository->findOneBy(['nis' => $nis]);

            $isCitizenFound = (null !== $citizen);
        }

        return $this->render('citizen_search/index.html.twig', [
            'form' => $form->createView(),
            'citizen' => $citizen,
            'isCitizenFound' => $isCitizenFound,
        ]);
    }
}
