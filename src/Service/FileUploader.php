<?php

namespace App\Service;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectory;
    private $slugger;
    protected $em;

    public function __construct($targetDirectory, SluggerInterface $slugger, EntityManagerInterface $entityManager)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->em = $entityManager;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileExtension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $fileName = $safeFilename.'-'.uniqid().'.'.$fileExtension;
        $sizeFile = $file->getSize();
        $typeFile = $file->getMimeType();

        try {
            $file->move($this->getTargetDirectory(), $fileName);

            $media = new Media();
            $media->setName($fileName);
            $media->setSize($sizeFile);
            $media->setType($typeFile);
            $media->setCreatedAt(new \DateTime());
            $media->setUpdatedAt(new \DateTime());
        } catch (FileException $e) {
            $this->addFlash(
                'error',
                'Error on upload file : '.$e
            );
        }

        return $media;
    }

    public function delete(Media $media)
    {
        $filesystem = new Filesystem();
        $path = $this->getTargetDirectory().$media->getName();

        if ($filesystem->exists($path)) {
            try {
                $this->em->remove($media);
                $this->em->flush();
                $result = $filesystem->remove($path);

                return true;
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
        } else {
            return false;
        }

        $this->addFlash(
            'error',
            'Error on delete file : '.$e
        );

        return false;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
