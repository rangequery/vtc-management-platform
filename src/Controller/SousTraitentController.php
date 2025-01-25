<?php

namespace App\Controller;

use App\Entity\SousTraitent;
use App\Form\SousTraitentType;
use App\Repository\SousTraitentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('private/sous-traitent')]
class SousTraitentController extends AbstractController
{
    #[Route('/', name: 'app_sous_traitent_index', methods: ['GET'])]
    public function index(SousTraitentRepository $sousTraitentRepository): Response
    {
        $sous_traitent = new SousTraitent();
        $form = $this->createForm(SousTraitentType::class, $sous_traitent);

        return $this->render('sous_traitent/index.html.twig', [
            'sous_traitents' => $sousTraitentRepository->findBy([],['nom' => 'ASC']),
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_sous_traitent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sousTraitent = new SousTraitent();
        $form = $this->createForm(SousTraitentType::class, $sousTraitent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sousTraitent);
            $entityManager->flush();

            return $this->redirectToRoute('app_sous_traitent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sous_traitent/new.html.twig', [
            'sous_traitent' => $sousTraitent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sous_traitent_show', methods: ['GET'])]
    public function show(SousTraitent $sousTraitent): Response
    {
        return $this->render('sous_traitent/show.html.twig', [
            'sous_traitent' => $sousTraitent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sous_traitent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SousTraitent $sousTraitent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SousTraitentType::class, $sousTraitent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sous_traitent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sous_traitent/_modal_edit.html.twig', [
            'sous_traitent' => $sousTraitent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sous_traitent_delete', methods: ['POST'])]
    public function delete(Request $request, SousTraitent $sousTraitent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sousTraitent->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($sousTraitent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sous_traitent_index', [], Response::HTTP_SEE_OTHER);
    }
}
