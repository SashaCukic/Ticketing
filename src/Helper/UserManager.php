<?php

namespace App\Helper;

use App\Service\FileUploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    /** @var EntityManager */
    protected $em;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /** @var FileUploader */
    private $fileUploader;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader)
    {
        $this->em = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->fileUploader = $fileUploader;
    }

    public function addUser($form, $user)
    {
        if ($form->get('plainPassword')->getData() !== null) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->eraseCredentials();
        }

        /** @var UploadedFile $mediaFile */
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            if ($user->getMedia()) {
                //-- Remove old Media
                $oldMedia = $user->getMedia();
                $user->setMedia(null);
                $this->fileUploader->delete($oldMedia);
            }
            $media = $this->fileUploader->upload($imageFile);
            $user->setMedia($media);
        }

        if (!$user->getIsActive()) {
            $user->setIsActive(false);
        }

        $user->setRoles(['ROLE_USER']);
        $user->setUpdatedAt(new \DateTime());
        $user->setCreatedAt(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();

        return true;
    }

    public function editUser($form, $user)
    {
        if ($form->get('plainPassword')->getData() !== null) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->eraseCredentials();
        }

        /** @var UploadedFile $mediaFile */
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            if ($user->getMedia()) {
                //-- Remove old Media
                $oldMedia = $user->getMedia();
                $user->setMedia(null);
                $this->fileUploader->delete($oldMedia);
            }
            $media = $this->fileUploader->upload($imageFile);
            $user->setMedia($media);
        }

        if (!$user->getIsActive()) {
            $user->setIsActive(false);
        }

        $user->setUpdatedAt(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();

        return true;
    }
}
