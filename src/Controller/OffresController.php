<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Form\AjoutFormType;
use App\Repository\OffresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffresController extends AbstractController
{
    #[Route('/offres', name: 'app_offres')]
    public function index(OffresRepository $offresRepository): Response
    {
        $offres = $offresRepository->findAll();
        return $this->render('offres/index.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/offres/ajout', name: 'app_offres_ajout')]
    public function ajout(Request $request, EntityManagerInterface $entitymanager): Response
    {
        $offres = new Offres();

        $form = $this->createForm(AjoutFormType::class, $offres);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entitymanager->persist($offres);
            $entitymanager->flush();
            $this->addFlash('success', 'Vous avez ajouter une offre');
            return $this->redirectToRoute('app_offres');
        }


        return $this->render('offres/ajout.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/offres/modifier/{id}', name: 'app_offres_modifier')]
    public function modifier(OffresRepository $offresRepository, $id, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $offres = $offresRepository->find($id);

        $form = $this->createForm(AjoutFormType::class, $offres);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($offres);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Vous avez bien modifier un employe');
            return $this->redirectToRoute('app_offres');
        }

        return $this->render('offres/modifier.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/offres/voirDetail/{id}', name: 'app_offres_voirDetail')]
    public function voirDetail(OffresRepository $offresRepository, $id): Response
    {
        $offres = $offresRepository->find($id);
        return $this->render('offres/voirDetail.html.twig', ['offre' => $offres]);
    }

    #[Route('/offres/suppression/{id}', name: 'app_offres_suppression')]
    public function suppression($id, OffresRepository $offresRepository, EntityManagerInterface $entityManagerInterface)
    {
        $offres = $offresRepository->find($id);

        $entityManagerInterface->remove($offres);
        $entityManagerInterface->flush();
        $this->addFlash('success', 'Vous avez bien supprimer une offre');
        return $this->redirectToRoute('app_offres');
    }
}
