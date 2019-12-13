<?php
/**
 * Created by PhpStorm.
 * User: Hella Boukari
 * Date: 14/02/2018
 * Time: 16:43
 */

namespace JardinDenfant\ProfilJDBundle;


use Symfony\Component\HttpFoundation\File\UploadedFile;


class ImageUpload
{


    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDir(), $fileName);

        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}