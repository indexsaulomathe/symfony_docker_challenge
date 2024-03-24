<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CitizenRepository;
use Symfony\Component\Routing\Annotation\Route;

class CitizenSearchController extends AbstractController
{
    #[Route("/search", name: "app_citizen_search")]
    public function index(Request $request, CitizenRepository $citizenRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('nis', TextType::class, [
                'attr' => [
                    'placeholder' => 'Digite o NIS para pesquisa',
                    'class' => 'form-control',
                    'maxlength' => 11
                ],
                'label' => 'NIS',
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Buscar',
                'attr' => ['class' => 'btn btn-primary mt-2']
            ])
            ->getForm();

        $form->handleRequest($request);
        $citizen = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $nis = $form->get('nis')->getData();
            $citizen = $citizenRepository->findOneBy(['nis' => $nis]);

            if (!$citizen) {
                $this->addFlash('warning', 'Cidadão não encontrado.');
            }
        }

        return $this->render('citizen_search/search.html.twig', [
            'form' => $form->createView(),
            'citizen' => $citizen
        ]);
    }
}