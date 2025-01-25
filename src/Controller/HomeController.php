<?php

namespace App\Controller;

use App\Service\Telegram;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(Telegram $telegram): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_service_index');
        }
        return $this->redirectToRoute('app_service_index');

    }
}
