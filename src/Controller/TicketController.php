<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\TicketLog;
use App\Form\TicketFormType;
use App\Form\TicketLogType;
use App\Helper\TicketManager;
use App\Service\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ticket")
 */
class TicketController extends AbstractController
{
    /** @var TicketManager */
    private $ticketManager;
    /** @var Logger */
    private $logger;

    public function __construct(TicketManager $ticketManager, Logger $logger)
    {
        $this->ticketManager = $ticketManager;
        $this->logger = $logger;
    }

    /**
     * @Route("/new", name="ticket_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketFormType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //-- Add Ticket
            $this->ticketManager->addTicket($form, $ticket);

            return $this->redirectToRoute('main');
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticket_show", methods={"GET","POST"})
     */
    public function show(Request $request, Ticket $ticket): Response
    {
        $ticketLog = new TicketLog();
        $formComment = $this->createForm(TicketLogType::class, $ticketLog);
        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            //-- Add TicketLog
            $this->logger->createTicketLog($formComment->get('content')->getData(), $this->getUser(), $ticket);

            return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
        }

        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
            'formComment' => $formComment->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ticket_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ticket $ticket): Response
    {
        $form = $this->createForm(TicketFormType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //-- Edit Ticket
            $this->ticketManager->editTicket($form, $ticket);

            return $this->redirectToRoute('main');
        }

        return $this->render('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ticket_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ticket $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('main');
    }
}
