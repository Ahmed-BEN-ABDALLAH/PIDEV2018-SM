<?php

/**
 * Created by PhpStorm.
 * User: Hella Boukari
 * Date: 14/02/2018
 * Time: 16:55
 */
namespace JardinDenfant\ProfilJDBundle\EventListener;

use JardinDenfant\ProfilJDBundle\Entity\Evenement;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use JardinDenfant\ProfilJDBundle\Entity\ProfilJD;
use JardinDenfant\ProfilJDBundle\ImageUpload;

class ImageUploadListener
{
    private $uploader;

    public function __construct(ImageUpload $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof ProfilJD) {
            return;
        }


        $file = $entity->getImage();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploader->upload($file);
        $entity->setImage($fileName);
    }
}