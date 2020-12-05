<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main", methods={"GET"})
     */
    public function index(TicketRepository $ticketRepository, UserRepository $userRepository): Response
    {
        $clients = null;
        if ($this->isGranted('ROLE_ADMIN')) {
            $tickets = $ticketRepository->findAll();
            $clients = $userRepository->findByRole('ROLE_USER');
        } elseif ($this->isGranted('ROLE_USER')) {
            $tickets = $ticketRepository->findByCreatedBy($this->getUser());
        } else {
            $this->denyAccessUnlessGranted('ROLE_USER');
        }

        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets,
            'clients' => $clients,
        ]);
    }
}
