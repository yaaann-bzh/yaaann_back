<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileDownloader  
{
    private $imagesDirectory;
    private $slugger;

    public function __construct($imagesDirectory, SluggerInterface $slugger)
    {
        $this->imagesDirectory = $imagesDirectory;
        $this->slugger = $slugger;
    }

    public function download($filename)
    {
        $filename = $this->imagesDirectory . '/'. $filename;

        if (!file_exists($filename)) {
            throw new NotFoundHttpException("Fichier inconnu");
        }

        return new File($filename);
    }

    public function getImagesDirectory()
    {
        return $this->imagesDirectory;
    }
}
