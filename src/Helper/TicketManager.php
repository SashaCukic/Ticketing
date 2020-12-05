<?php

namespace App\Helper;

use App\Service\FileUploader;
use App\Service\Logger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TicketManager
{
    /** @var EntityManager */
    protected $em;

    /** @var FileUploader */
    private $fileUploader;

    /** @var Logger */
    private $logger;

    /** @var Security */
    private $security;

    public function __construct(EntityManagerInterface $entityManager, FileUploader $fileUploader, Logger $logger, Security $security)
    {
        $this->em = $entityManager;
        $this->fileUploader = $fileUploader;
        $this->logger = $logger;
        $this->security = $security;
    }

    public function addTicket($form, $ticket)
    {
        $user = $this->security->getUser();

        /** @var UploadedFile $mediaFile */
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            if ($ticket->getMedia()) {
                //-- Remove old Media
                $oldMedia = $ticket->getMedia();
                $ticket->setMedia(null);
                $this->fileUploader->delete($oldMedia);
            }
            $media = $this->fileUploader->upload($imageFile);
            $ticket->setMedia($media);
        }

        $ticket->setTitle($form->get('title')->getData());
        $ticket->setDescription($form->get('description')->getData());
        $ticket->setTicketPriority($form->get('ticketPriority')->getData());
        $ticket->setTicketType($form->get('ticketType')->getData());
        $ticket->setCreatedBy($user);
        $ticket->setUpdatedAt(new \DateTime());
        $ticket->setCreatedAt(new \DateTime());
        $this->em->persist($ticket);
        $this->em->flush();

        $this->logger->createTicketLog('a créé ce ticket', $user, $ticket);

        return true;
    }

    public function editTicket($form, $ticket)
    {
        $user = $this->security->getUser();

        /** @var UploadedFile $mediaFile */
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            if ($ticket->getMedia()) {
                //-- Remove old Media
                $oldMedia = $ticket->getMedia();
                $ticket->setMedia(null);
                $this->fileUploader->delete($oldMedia);
            }
            $media = $this->fileUploader->upload($imageFile);
            $ticket->setMedia($media);
        }

        $ticket->setTitle($form->get('title')->getData());
        $ticket->setDescription($form->get('description')->getData());
        $ticket->setTicketPriority($form->get('ticketPriority')->getData());
        $ticket->setTicketType($form->get('ticketType')->getData());
        $ticket->setUpdatedAt(new \DateTime());
        $this->em->persist($ticket);
        $this->em->flush();

        $this->logger->createTicketLog('a modifié ce ticket', $user, $ticket);

        return true;
    }
}
