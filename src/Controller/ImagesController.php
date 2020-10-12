<?php

namespace App\Controller;

use App\Entity\ProjectImage;
use App\Service\FileDownloader;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 */
class ImagesController extends AbstractController
{
    /**
     * @Route(
     *      path = "/admin/images",
     *      name = "projects_image_add",
     *      methods={"POST"}
     * )
     */
    public function uploadAction(Request $request, ValidatorInterface $validator, FileUploader $fileUploader)
    {
        $image = new ProjectImage();
        $file = $request->files->get('file');
        $image->setImageFile($file);

        $errors = $validator->validate($image);

        if (count($errors)) 
        {
            $message = 'File sent is not valid. Here are the errors you need to correct: ';
            foreach ($errors as $error) {
                $message .= sprintf("Property %s: %s ", $error->getPropertyPath(), $error->getMessage());
            }
            return new Response($message, Response::HTTP_UNSUPPORTED_MEDIA_TYPE, array('Content-Type' => 'text/html'));
        }

        $imageFilename = $fileUploader->upload($file);

        if ($imageFilename instanceof Response) {
            return $imageFilename;
        }

        return new Response(
            $this->generateUrl('projects_image_show', array('filename' => $imageFilename), UrlGeneratorInterface::ABSOLUTE_URL),
            /* $this->generateUrl('home', array(), UrlGeneratorInterface::ABSOLUTE_URL) . 'assets/projects/' . $imageFilename, */
            Response::HTTP_CREATED, 
            array('Content-Type' => 'text/html')
        );
    }

    /**
     * @Route(
     *      path = "/images/{filename}",
     *      name = "projects_image_show",
     *      methods={"GET"}
     * ) 
     */
    public function showAction($filename, FileDownloader $downloader)
    {
        $image = $downloader->download($filename);

        return $this->file($image);
    }

}
