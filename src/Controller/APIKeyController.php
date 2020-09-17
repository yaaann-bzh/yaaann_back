<?php

namespace App\Controller;

use App\Entity\APIKey;
use App\Form\APIKeyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class APIKeyController extends AbstractController
{
    /**
     * @Route("/admin/apikey", name="add_apikey")
     */
    public function index(Request $request)
    {
        $apiKey = new APIKey();

        $form = $this->createForm(APIKeyType::class, $apiKey);
        $form->handleRequest($request);
        $apiKey
            ->setApiKey(bin2hex(random_bytes(10)))
            ->setCreationDate(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($apiKey);
            $em->flush();

            return $this->render('apikey/created.html.twig', [
                'apikey' => $apiKey,
            ]);
        }

        return $this->render('apikey/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
