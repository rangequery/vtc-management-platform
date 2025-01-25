<?php

namespace App\Controller;

use App\Entity\Chauffeur;
use App\Entity\Service;
use App\Entity\Status;
use App\Entity\Type;
use App\Form\ServiceFilterType;
use App\Form\ServiceType;
use App\Repository\AdresseRepository;
use App\Repository\DemandeurRepository;
use App\Repository\ServiceRepository;
use App\Service\MailerService;
use App\Service\Telegram;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('private/service')]
class ServiceController extends AbstractController
{

    private $chatIdManager = "-4509876234"; // Prod Manager
    private $messageToDriver;

    public function __construct()
    {


    }

    private function messageChauffeur($titre, $type, $ref, $date, $price, $pax, $from, $to, $infoClient)
    {

        // Chauffeur
        $message = $titre . " " . $type . " - " . $ref . "\n"
            . "🕘 " . $this->formatServiceDate($date) . "\n"
            . "Prix " . $price . "€ - PAX : " . $pax . "\n"
            . $from . " ➡️ " . $to . "\n"
            . "Client : \n"
            . $infoClient . "\n";

        return $message;

    }

//    private $chatIdManager = "-4586990607"; // Test Manager

    #[Route('/', name: 'app_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        // Pour le modal création du formulaire
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);

        $services = $serviceRepository->findBy([], ['serviceAt' => 'ASC']);
        if (empty($services)) {
            $services = [];  // Retourne un tableau vide si aucun service n'est trouvé
        }

        return $this->render('service/index.html.twig', [
            'form' => $form,
            'services' => $services,
        ]);
    }

    #[Route('/archives', name: 'app_service_passee', methods: ['GET', 'POST'])]
    public function indexPasse(ServiceRepository $serviceRepository, Request $request): Response
    {

        // Pour le modal création du formulaire
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);

        // Créer le formulaire de filtre
        $filterForm = $this->createForm(ServiceFilterType::class);
        $filterForm->handleRequest($request);

        // Définir les critères de filtrage
        $criteria = [];
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();

            if ($data['adresseNom']) {
                $criteria['adresse.nom'] = $data['adresseNom'];
            }
            if ($data['chauffeurNom']) {
                $criteria['chauffeur.nom'] = $data['chauffeurNom'];
            }
            if ($data['demandeurNom']) {
                $criteria['demandeur.nom'] = $data['demandeurNom'];
            }
            if ($data['startDate'] && $data['endDate']) {
                $criteria['serviceAt'] = [
                    'start' => $data['startDate'],
                    'end' => $data['endDate']
                ];
            }
        }

        $services = $serviceRepository->findByCriteria($criteria);


        return $this->render('service/archives.html.twig', [
//            'services' => $serviceRepository->findAll(),
            'form' => $form,
            'services' => $services,
            //'services' => $serviceRepository->findBy([], ['serviceAt' => 'ASC']),
            'filterForm' => $filterForm->createView(),
        ]);
    }

    #[Route('/chauffeur/{slug}', name: 'app_service_chauffeur', methods: ['GET'])]
    public function indexChauffeur(ServiceRepository $serviceRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger, $slug): Response
    {
        // Pour le modal création du formulaire
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);

        $chauffeur = $entityManager->getRepository(Chauffeur::class)->findOneBy(['slug' => $slug]);

        return $this->render('service/index.html.twig', [
//            'services' => $serviceRepository->findAll(),
            'form' => $form,
            'services' => $serviceRepository->findBy(['chauffeur' => $chauffeur], ['serviceAt' => 'ASC']),
            'chauffeur' => $chauffeur,
        ]);
    }

    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->setCreatedAt(new DateTimeImmutable());
            $status = $entityManager->getRepository(Status::class)->find(1);
            $service->setStatus($status);
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->setUpdatedAt(new DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editModal', name: 'app_service_editModal', methods: ['GET', 'POST'])]
    public function editModal(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->setUpdatedAt(new DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service/_modal_edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/api/remove/{id}/{route}', name: 'app_service_remove', methods: ['GET'])]
    public function remove(Service $service, EntityManagerInterface $entityManager, $id, ServiceRepository $serviceRepository, $route): Response
    {

        $serviceRepository->findOneBy(['id'=> $id]);

            $entityManager->remove($service);
            $entityManager->flush();


        return $this->redirectToRoute($route, [], Response::HTTP_SEE_OTHER);
    }


    public function formatServiceDate(?DateTimeImmutable $serviceAt): string
    {
//        return $serviceAt ? $serviceAt->format('d-m-Y - H:i') : 'Date non définie';
        if (!$serviceAt) {
            return 'Date non définie';
        }

        // Créer un formateur de date pour le format jour mois année et l'heure
        $formatter = new \IntlDateFormatter(
            'fr_FR',
            \IntlDateFormatter::LONG, // Format de la date longue
            \IntlDateFormatter::SHORT  // Format de l'heure courte (ex: 14:30)
        );

        return $formatter->format($serviceAt);
    }

    /*Service envoi telegram*/
    #[Route('/envoi/{idService}', name: 'app_service_envoi', methods: ['GET'])]
    public function envoi(Telegram               $telegram, ServiceRepository $serviceRepository,
                          EntityManagerInterface $entityManager, $idService, MailerService $mailerService
    ): JsonResponse
    {
        // Récupérer le service par ID
        $service = $serviceRepository->findOneBy(['id' => $idService]);

        // Vérifiez si le service a été trouvé
        if (!$service) {
            return new JsonResponse(['success' => false, 'message' => 'Service non trouve.'], Response::HTTP_NOT_FOUND);
        }


        // Encoder les adresses d'origine et de destination
        $origin = urlencode($service->getPickUpFrom()->getAdresse() . " " . $service->getPickUpFrom()->getVille() . " " . $service->getPickUpFrom()->getCodePostal()); // Adresse de départ
        $destination = urlencode($service->getPickUpTo()->getAdresse() . " " . $service->getPickUpTo()->getVille() . " " . $service->getPickUpTo()->getCodePostal()); // Adresse de destination

        // Chauffeur
//        $message = "🔴 NOUVELLE COURSE\n"
//            . $service->getType()->getNom() . " - " . $service->getReferenceNumber() . "\n"
//            . "🕘 " . $this->formatServiceDate($service->getServiceAt()) . "\n"
//            . "Prix " . $service->getMontantHt() . "€ - PAX : " . $service->getPax() . "\n"
//            . $service->getPickUpFrom()->getNom() . " ➡️ " . $service->getPickUpTo()->getNom() . "\n"
//            . "Client : \n"
//            . $service->getInfoClient() . "\n";

        $message = $this->messageChauffeur("🔴",
            $service->getType()->getNom(), $service->getReferenceNumber(),
            $service->getServiceAt(), $service->getMontantHt(),
            $service->getPax(), $service->getPickUpFrom()->getNom(), $service->getPickUpTo()->getNom(),
            $service->getInfoClient()
        );

        if ($service->getChauffeur() !== null) {
            // Manager
            $messageManager = "🔴 NOUVELLE COURSE ENVOYER\n"
                . "🚘 " . $service->getChauffeur()->getNom() . "\n"
                . $service->getType()->getNom() . " - 🕘 " . $this->formatServiceDate($service->getServiceAt()) . "\n"
                . $service->getPickUpFrom()->getNom() . " ➡️ " . $service->getPickUpTo()->getNom() . "\n";
        } else {
            // Manager
            $messageManager = "🔵 NOUVELLE COURSE ENVOYER\n"
                . "🚘 " . $service->getChauffeur()->getNom() . "\n"
                . $service->getType()->getNom() . " - 🕘 " . $this->formatServiceDate($service->getServiceAt()) . "\n"
                . $service->getPickUpFrom()->getNom() . " ➡️ " . $service->getPickUpTo()->getNom() . "\n";

        }

        try {
            // Envoyer le message via Telegram pour le manager
//            $resultM = $telegram->sendMessageLite($messageManager, $this->chatIdManager);
//            $dataM = json_decode($resultM, true);
//            $message_idM = $dataM['result']['message_id'];

            if ($service->getChauffeur() !== null) {
                // Vérification si le service a été annuler pour le meme chauffeur si oui on supp le message
                if ($service->getStatus()->getId() === 6) { // 6 = Annuler
                    $telegram->deleteTelegramMessage($service->getChauffeur()->getChatId(), $service->getMessageIdChauffeur());
                    $telegram->deleteTelegramMessage($this->chatIdManager, $service->getMessageId());
                }

                // Envoyer le message via Telegram pour le nouveau chauffeur
                $result = $telegram->sendMessage($message, $service->getChauffeur()->getChatId());
                $data = json_decode($result, true);
                $message_id = $data['result']['message_id'];

                // Update du service avec les nouveaux element pour chauffeur
//                $service->setMessageId($message_idM);
                $service->setMessageIdChauffeur($message_id);
            } else {
                $service->setMessageId($message_idM);
//                $mailerService->sendEmail(
//                    'sayn.emcorp@gmail.com',
//                    'contact@sayn.me',
//                    'Nouvelle course',
//                    'Sending emails is fun again!',
//                    '<p>🔴 NOUVELLE COURSE  </p>'
//                );
                // return new JsonResponse(['success' => true, 'message' => 'Message envoye au sous traitent.']);
            }
            $status = $entityManager->getRepository(Status::class)->find(2);
            $service->setStatus($status);
            $service->setNotification($message);
            $entityManager->persist($service);
            $entityManager->flush();

            // Retourner une réponse JSON indiquant le succès
            return new JsonResponse(['success' => true, 'message' => 'Message envoye avec succes.']);
        } catch (\Exception $e) {
            // En cas d'erreur lors de l'envoi du message
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de l\'envoi du message : ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Confirmation par le chauffeur
    #[Route('/confirmation/{message_id}', name: 'app_home_confirmation')]
    public function confirmation(Telegram $telegram, ServiceRepository $serviceRepository, EntityManagerInterface $entityManager, $message_id): JsonResponse
    {
        // Vérifier que l'ID du message est valide
        if (!$message_id) {
            // Gérer le cas où l'ID n'est pas présent
            return new JsonResponse(['success' => false, 'message' => 'message_id vide.'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer le service par message_id_chauffeur
        $service = $serviceRepository->findOneBy(['message_id_chauffeur' => $message_id]);
        // Vérifiez si le service a été trouvé
        if (!$service) {
            return new JsonResponse(['success' => false, 'message' => 'Service non trouve.'], Response::HTTP_NOT_FOUND);
        }


        if ($service->getStatus()->getId() === 3) {
            // Retourner une réponse JSON indiquant le succès
            return new JsonResponse(['success' => true, 'message' => 'Course deja confirmer.']);
        }

        // Message que vous souhaitez envoyer pour confirmer la course
        $updatedMessage = "🟠 COURSE CONFIRMEE\n"
            . $service->getType()->getNom() . " - 🕘 " . $this->formatServiceDate($service->getServiceAt()) . "\n"
            . $service->getPickUpFrom()->getNom() . "➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "Prix " . $service->getMontantHt() . "€ - PAX : " . $service->getPax() . "\n"
            . $service->getPickUpFrom()->getNom() . " ➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "Client : \n"
            . $service->getInfoClient() . "\n";


        $updatedMessageManager = "🔴 🟠 COURSE CONFIRMEE\n"
            . $service->getType()->getNom() . " - 🕘 " . $this->formatServiceDate($service->getServiceAt()) . "\n"
            . "🚘 " . $service->getChauffeur()->getNom() . " " . $service->getChauffeur()->getPrenom() . "\n"
            . "Prix " . $service->getMontantHt() . "€ - PAX : " . $service->getPax() . "\n"
            . $service->getPickUpFrom()->getNom() . " ➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "Client : \n"
            . $service->getInfoClient() . "\n";


        try {
            // Appeler la méthode pour éditer le message existant
            $result = $telegram->updateMessageConfirmation($message_id, $service->getChauffeur()->getChatId(), $updatedMessage);

            // Delete du message manager puis envoi d'un nouveau
            $telegram->deleteTelegramMessage($this->chatIdManager, $service->getMessageId());

            // Envoi du message au manager
            $resultM = $telegram->sendMessageLite($updatedMessageManager, $this->chatIdManager);
            $dataM = json_decode($resultM, true);
            $message_idM = $dataM['result']['message_id'];

            // Update du service avec les nouveaux element
            $status = $entityManager->getRepository(Status::class)->find(3);
            $service->setStatus($status);
            $service->setMessageId($message_idM);
            $entityManager->persist($service);
            $entityManager->flush();

            // Retourner une réponse JSON indiquant le succès
            return new JsonResponse(['success' => true, 'message' => 'Message modifier avec succes.']);
        } catch (\Exception $e) {
            // En cas d'erreur lors de l'envoi du message
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de l\'envoi du message : ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // depart par le chauffeur
    #[Route('/depart/{message_id}', name: 'app_home_depart')]
    public function depart(Telegram $telegram, ServiceRepository $serviceRepository, EntityManagerInterface $entityManager, $message_id): JsonResponse
    {
        // Vérifier que l'ID du message est valide
        if (!$message_id) {
            // Gérer le cas où l'ID n'est pas présent
            return new JsonResponse(['success' => false, 'message' => 'message_id vide.'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer le service par message_id_chauffeur
        $service = $serviceRepository->findOneBy(['message_id_chauffeur' => $message_id]);

        // Vérifiez si le service a été trouvé
        if (!$service) {
            return new JsonResponse(['success' => false, 'message' => 'Service non trouve.'], Response::HTTP_NOT_FOUND);
        }


        if ($service->getStatus()->getId() === 8) {
            // Retourner une réponse JSON indiquant le succès
            return new JsonResponse(['success' => true, 'message' => 'Course deja depart envoyer.']);
        }

        // Message que vous souhaitez envoyer pour confirmer la course
        $updatedMessage = "DEPART ✅\n"
            . $service->getType()->getNom() . " - 🕘 " . $this->formatServiceDate($service->getServiceAt()) . "\n"
            . $service->getPickUpFrom()->getNom() . "➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "Prix " . $service->getMontantHt() . "€ - PAX : " . $service->getPax() . "\n"
            . $service->getPickUpFrom()->getNom() . " ➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "Client : \n"
            . $service->getInfoClient() . "\n";


        $updatedMessageManager = "🔴 🟠 DEPART ✅\n"
            . $service->getType()->getNom() . " - 🕘 " . $this->formatServiceDate($service->getServiceAt()) . "\n"
            . "🚘 " . $service->getChauffeur()->getNom() . " " . $service->getChauffeur()->getPrenom() . "\n"
            . $service->getPickUpFrom()->getNom() . "➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "Prix " . $service->getMontantHt() . "€ - PAX : " . $service->getPax() . "\n"
            . $service->getPickUpFrom()->getNom() . " ➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "Client : \n"
            . $service->getInfoClient() . "\n";


        try {
            // Appeler la méthode pour éditer le message existant
            $result = $telegram->updateMessageDepart($message_id, $service->getChauffeur()->getChatId(), $updatedMessage);

            // Delete du message manager puis envoi d'un nouveau
            $telegram->deleteTelegramMessage($this->chatIdManager, $service->getMessageId());

            // Envoi du message au manager
            $resultM = $telegram->sendMessageLite($updatedMessageManager, $this->chatIdManager);
            $dataM = json_decode($resultM, true);
            $message_idM = $dataM['result']['message_id'];

            // Update du service avec les nouveaux element
            $status = $entityManager->getRepository(Status::class)->find(8);
            $service->setStatus($status);
            $service->setMessageId($message_idM);
            $entityManager->persist($service);
            $entityManager->flush();

            // Retourner une réponse JSON indiquant le succès
            return new JsonResponse(['success' => true, 'message' => 'Message modifier avec succes.']);
        } catch (\Exception $e) {
            // En cas d'erreur lors de l'envoi du message
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de l\'envoi du message : ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/finished/{message_id}', name: 'app_home_finished')]
    public function finished(Telegram $telegram, ServiceRepository $serviceRepository, EntityManagerInterface $entityManager, $message_id): JsonResponse
    {
        // Vérifier que l'ID du message est valide
        if (!$message_id) {
            // Gérer le cas où l'ID n'est pas présent
            return new JsonResponse(['success' => false, 'message' => 'message_id vide.'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer le service par message_id
        $service = $serviceRepository->findOneBy(['message_id_chauffeur' => $message_id]);

        // Vérifiez si le service a été trouvé
        if (!$service) {
            return new JsonResponse(['success' => false, 'message' => 'Service non trouve.'], Response::HTTP_NOT_FOUND);
        }

        // Update du service avec les nouveaux element
        $status = $entityManager->getRepository(Status::class)->find(4);
        $service->setStatus($status);
        $service->setFinishedAt(new \DateTimeImmutable('now'));
        $entityManager->persist($service);
        $entityManager->flush();

        // Message que vous souhaitez envoyer pour confirmer la course
        $updatedMessage = "🏁 COURSE TERMINER 🔥\n"
            . $service->getType()->getNom() . " - 🕘 " . $this->formatServiceDate($service->getServiceAt()) . "\n"
            . $service->getPickUpFrom()->getNom() . "➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "Prix " . $service->getMontantHt() . "€ - PAX : " . $service->getPax() . "\n"
            . $service->getPickUpFrom()->getNom() . " ➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "Client : \n"
            . $service->getInfoClient() . "\n";

        $updatedMessageManager = "🔴 🟠 ✅ 🏁 Terminée\n"
            . $service->getType()->getNom() . " - 🕘 " . $this->formatServiceDate($service->getServiceAt()) . "\n"
            . $service->getPickUpFrom()->getNom() . "➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "Prix " . $service->getMontantHt() . "€ - PAX : " . $service->getPax() . "\n"
            . $service->getPickUpFrom()->getNom() . " ➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "Client : \n"
            . $service->getInfoClient() . "\n";

        // Gérer la réponse de l'API Telegram si nécessaire
        try {
            // Appeler la méthode pour éditer le message existant
            $result = $telegram->updateMessageFinished($message_id, $service->getChauffeur()->getChatId(), $updatedMessage);

            // Delete du message manager puis envoi d'un nouveau
            $telegram->deleteTelegramMessage($this->chatIdManager, $service->getMessageId());

            // Envoi du message au manager
            $resultM = $telegram->sendMessageLite($updatedMessageManager, $this->chatIdManager);
            $dataM = json_decode($resultM, true);
            $message_idM = $dataM['result']['message_id'];

            // Update du service avec les nouveaux element
            $status = $entityManager->getRepository(Status::class)->find(4);
            $service->setStatus($status);
            $service->setMessageId($message_idM);
            $entityManager->persist($service);
            $entityManager->flush();

            // Retourner une réponse JSON indiquant le succès
            return new JsonResponse(['success' => true, 'message' => 'Message modifier avec succes.']);
        } catch (\Exception $e) {
            // En cas d'erreur lors de l'envoi du message
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de l\'envoi du message : ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/annuler/{service_id}', name: 'app_home_annuler')]
    public function annuler(Telegram $telegram, ServiceRepository $serviceRepository, EntityManagerInterface $entityManager, $service_id): JsonResponse
    {
        // Vérifier que l'ID du message est valide
        if (!$service_id) {
            // Gérer le cas où l'ID n'est pas présent
            return new JsonResponse(['success' => false, 'message' => 'service_id vide.'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer le service par service_id
        $service = $serviceRepository->findOneBy(['id' => $service_id]);

        // Vérifiez si le service a été trouvé
        if (!$service) {
            return new JsonResponse(['success' => false, 'message' => 'Service non trouve.'], Response::HTTP_NOT_FOUND);
        }

        // Message que vous souhaitez envoyer pour confirmer la course
        $updatedMessage = "❌ COURSE ANNULER\n"
            . $service->getType()->getNom() . " - 🕘 " . $this->formatServiceDate($service->getServiceAt()) . "\n"
            . $service->getPickUpFrom()->getNom() . "➡️ " . $service->getPickUpTo()->getNom() . "\n"
            . "CLIENTS : \n"
            . $service->getInfoClient() . "\n"
            . "\n👍 08 avril 2024 - 13h50"
            . "\n🏁 08 avril 2024 - 15h45";
        try {
            // Chauffeur
            $telegram->deleteTelegramMessage($service->getChauffeur()->getChatId(), $service->getMessageIdChauffeur());

            // Envoi du message au chauffeur sans lien
            $resultM = $telegram->sendMessageLite($updatedMessage, $service->getChauffeur()->getChatId());
            $dataM = json_decode($resultM, true);
            $message_idChauffeur = $dataM['result']['message_id'];

            // Manager
            $telegram->updateMessageLite($service->getMessageId(), $this->chatIdManager, $updatedMessage);

            // Update du service avec les nouveaux element dans la table
            $status = $entityManager->getRepository(Status::class)->find(6);
            $service->setStatus($status);
            $service->setMessageIdChauffeur($message_idChauffeur);
            $entityManager->persist($service);
            $entityManager->flush();

            // Retourner une réponse JSON indiquant le succès
            return new JsonResponse(['success' => true, 'message' => 'Message modifier avec succes.']);
        } catch (\Exception $e) {
            // En cas d'erreur lors de l'envoi du message
            return new JsonResponse(['success' => false, 'message' => 'Erreur lors de l\'envoi du message : ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/archiver/{service_id}', name: 'app_service_archiver', methods: ['GET'])]
    public function archiver(ServiceRepository $serviceRepository, EntityManagerInterface $entityManager, $service_id): JsonResponse
    {

        // Vérifier que l'ID du message est valide
        if (!$service_id) {
            // Gérer le cas où l'ID n'est pas présent
            return new JsonResponse(['success' => false, 'message' => 'service_id vide.'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer le service par service_id
        $service = $serviceRepository->findOneBy(['id' => $service_id]);

        // Vérifiez si le service a été trouvé
        if (!$service) {
            return new JsonResponse(['success' => false, 'message' => 'Service non trouve.'], Response::HTTP_NOT_FOUND);
        }
        // Update du service avec les nouveaux element
        $status = $entityManager->getRepository(Status::class)->find(5);
        $service->setStatus($status);
        $entityManager->persist($service);
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Message archiver avec succes.']);
    }

    #[Route('/test-mail', name: 'app_service_testMail', methods: ['GET'])]
    public function testemail(MailerService $mailer): Response
    {

        $mailer->sendEmail(
            'sayn.emcorp@gmail.com',
            'contact@sayn.me',
            'app_service_testMail',
            'Sending emails is fun again!',
            '<p>See Twig integration for better HTML integration!</p>'
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            ''
        ]);
    }

    #[Route('/planning/{id}', name: 'app_service_planning', methods: ['GET'])]
    public function planning(ServiceRepository $serviceRepository, $id)
    {
        $dompdf = new Dompdf();
        $servicesDate = $serviceRepository->findOneBy(['id' => $id]);

        $date = $servicesDate->getServiceAt();
        $startOfDay = $date->setTime(0, 0, 0);
        $endOfDay = $date->setTime(23, 59, 59);

        $services = $serviceRepository->createQueryBuilder('s')
            ->where('s.serviceAt BETWEEN :start AND :end')
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay)
            ->getQuery()
            ->getResult();


        $html = $this->renderView('service/pdf.html.twig', [
            'services' => $services,
            'dateService' => $servicesDate->getServiceAt()
        ]);


        $dompdf->loadHtml($html);
//        $dompdf->setPaper('A4', 'landscape');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('file.pdf', [
            'Attachment' => false
        ]);
        exit();
//        return new RedirectResponse($this->generateUrl('app_service_index'), Response::HTTP_SEE_OTHER);
    }

    #[Route('/from/api', name: 'app_service_apiIndex', methods: ['GET'])]
    public function indexApi(ServiceRepository $serviceRepository, EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);

        $status = $entityManager->getRepository(Status::class)->find(9);

        $services = $serviceRepository->findBy(['status'=> $status], ['id' => 'DESC']);

        if (empty($services)) {
            $services = [];  // Retourne un tableau vide si aucun service n'est trouvé
        }

        return $this->render('service/from_api.html.twig', [
            'form' => $form,
            'services' => $services,
        ]);
    }

    #[Route('/get/api', name: 'app_service_api', methods: ['POST', 'GET'])]
    public function api(Request $request, EntityManagerInterface $entityManager, AdresseRepository $adresseRepository
    , DemandeurRepository $demandeurRepository): Response
    {

        if ($request->isMethod('GET')) {
            return new Response("METHOD GET", 200, ['Content-Type' => 'text/plain; charset=utf-8']);
        } else {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            if($data['type'] === "départ"){
                $dataType = 1;
            }
            else{
                $dataType = 2;
            }


            $pickupFrom = $adresseRepository->findByPartialNom($this->removeSpecialChars($data['from'],[]));
            $pickupTo = $adresseRepository->findByPartialNom($this->removeSpecialChars($data['destination'],[]));
            $price = $this->removeSpecialChars($data['prix']);
            $serviceAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['datetime']);
            if ($serviceAt === false) {
                $serviceAt = null; // Si la date est invalide, on affecte null
            }

            $demandeur = $demandeurRepository->findByPartialNom($data['demandeur_nom']);
            // Transformation des informations
            // Ajout des informations si vide
            // Integration dans la table
            // Mettre en objet certaines informations
            $status = $entityManager->getRepository(Status::class)->find(9); // From API
            $type  = $entityManager->getRepository(Type::class)->find($dataType);


            $service = new Service();
            $service->setStatus($status);
            $service->setType($type);
            $service->setServiceAt($serviceAt);
            $service->setPickUpFrom($pickupFrom);
            $service->setPickUpTo($pickupTo);
            $service->setPax($data['pax']);
            $service->setReferenceNumber($data['reference']);
            $service->setMontantHt($price);
            $service->setInfoClient($data['client']."\n".$data['telephone_client']);
            $service->setInformationComplementaire($data['full_email']);
            $service->setDemandeur($demandeur);
            $service->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($service);
            $entityManager->flush();

            return new Response("Service successfully created", 200, ['Content-Type' => 'text/plain; charset=utf-8']);
        }

        return new Response("error : no data", 500, ['Content-Type' => 'text/plain; charset=utf-8']);

    }

    function removeSpecialChars(string $input, array $additionalChars = []): string
    {
        // Liste des caractères spéciaux à supprimer par défaut
        $specialChars = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '=', '+', '[', ']', '{', '}', '\\', '|', ';', ':', '"', '\'', '<', '>', ',', '.', '/', '?', '`', '~'];

        // Fusionner avec les caractères supplémentaires fournis
        $specialChars = array_merge($specialChars, $additionalChars);

        // Construire une expression régulière pour les caractères à supprimer
        $pattern = '/[' . preg_quote(implode('', $specialChars), '/') . ']/';

        // Supprimer les caractères spéciaux
        return preg_replace($pattern, '', $input);
    }

}
