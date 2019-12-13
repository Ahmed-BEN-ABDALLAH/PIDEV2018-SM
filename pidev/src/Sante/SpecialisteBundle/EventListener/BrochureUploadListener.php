<?php
/**
 * Created by PhpStorm.
 * User: amaln
 * Date: 25/02/2018
 * Time: 13:20
 */

namespace Sante\SpecialisteBundle\EventListener;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Sante\SpecialisteBundle\Entity\MedecinSpecialiste;
use Sante\SpecialisteBundle\FileUploader;

class BrochureUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
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
        if (!$entity instanceof MedecinSpecialiste) {
            return;
        }

        $file = $entity->getJustif();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setJustif($fileName);
        }
    }

}