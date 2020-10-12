<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader  
{
    private $imagesDirectory;
    private $slugger;

    public function __construct($imagesDirectory, SluggerInterface $slugger)
    {
        $this->imagesDirectory = $imagesDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'.'.$file->guessExtension();

        try {
            $file->move($this->getImagesDirectory(), $fileName);
        } catch (FileException $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, array('Content-Type' => 'text/html'));
        }

        return $fileName;
    }

    public function getImagesDirectory()
    {
        return $this->imagesDirectory;
    }
}
