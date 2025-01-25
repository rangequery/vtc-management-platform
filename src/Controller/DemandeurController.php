<?php

namespace App\Controller;

use App\Entity\Demandeur;
use App\Form\DemandeurType;
use App\Repository\DemandeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('private/demandeur')]
class DemandeurController extends AbstractController
{
    #[Route('/', name: 'app_demandeur_index', methods: ['GET'])]
    public function index(Request $request, DemandeurRepository $demandeurRepository): Response
    {
        $demandeur = new Demandeur();
        $form  = $this->createForm(DemandeurType::class, $demandeur);
        return $this->render('demandeur/index.html.twig', [
            'demandeurs' => $demandeurRepository->findBy([],['nom' => 'ASC']),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_demandeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $demandeur = new Demandeur();
        $form = $this->createForm(DemandeurType::class, $demandeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demandeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_demandeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demandeur/new.html.twig', [
            'demandeur' => $demandeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demandeur_show', methods: ['GET'])]
    public function show(Demandeur $demandeur): Response
    {
        return $this->render('demandeur/show.html.twig', [
            'demandeur' => $demandeur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demandeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demandeur $demandeur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandeurType::class, $demandeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_demandeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demandeur/_modal_edit.html.twig', [
            'demandeur' => $demandeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demandeur_delete', methods: ['POST'])]
    public function delete(Request $request, Demandeur $demandeur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($demandeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demandeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
