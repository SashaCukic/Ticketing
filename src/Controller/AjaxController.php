<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ajax", name="ajax_")
 */
class AjaxController extends AbstractController
{
    /** @var EntityManager */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/ticket/search", name="ticket_search", methods={"POST"})
     */
    public function ticketSearch(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $response = new JsonResponse();

            $userId = $request->request->get('id');
            $user = $this->em->getRepository(User::class)->findBy(['id' => $userId]);
            $tickets = $this->em->getRepository(Ticket::class)->findAll();
            $tickets = $this->em->getRepository(Ticket::class)->findByCreatedBy($user, ['createdAt' => 'DESC']);

            $response->setData([
                'html' => $this->renderView('components/tickets-table.html.twig', [
                    'tickets' => $tickets,
                ]),
            ]);

            return $response;
        }

        $this->denyAccessUnlessGranted('ROLE_USER');
    }
}
