<?php

namespace App\Service;

use App\Entity\TicketLog;
use Doctrine\ORM\EntityManagerInterface;

class Logger
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function createTicketLog($content, $user, $ticket)
    {
        $log = new TicketLog();
        $log->setContent($content);
        $log->setCreatedBy($user);
        $log->setTicket($ticket);
        $log->setCreatedAt(new \DateTime());

        $this->em->persist($log);
        $this->em->flush();
    }
}
