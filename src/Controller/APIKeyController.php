<?php

namespace App\Controller;

use App\Entity\APIKey;
use App\Form\APIKeyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
            ->setApiKey(bin2hex(random_bytes(15)))
            ->setCreationDate(new \DateTime());

        $repository = $this->getDoctrine()->getRepository(APIKey::class);
        $apikeys = $repository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($apiKey);
            $em->flush();

            return $this->redirectToRoute('show_apikey', array(
                'id' => $apiKey->getId())
            );
        }

        return $this->render('apikey/form.html.twig', [
            'form' => $form->createView(),
            'apikeys' => $apikeys,
            'tab' => 'apikey'
        ]);
    }

    /**
     * @Route("/admin/apikey/{id}", name="show_apikey")
     * @ParamConverter("apiKey", class="App\Entity\ApiKey")
     */
    public function show(APIKey $apiKey)
    {
        $repository = $this->getDoctrine()->getRepository(APIKey::class);
        $apikeys = $repository->findAll();

        return $this->render('apikey/created.html.twig', [
            'apikey' => $apiKey,
            'apikeys' => $apikeys,
            'tab' => 'apikey'
        ]);
    }

    /**
     * @Route("/admin/apikey/delete/{id}", name="delete_apikey")
     * @ParamConverter("apiKey", class="App\Entity\ApiKey")
     */
    public function delete(APIKey $apiKey)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($apiKey);
        $em->flush();

        $this->addFlash('notice', 'La clé a bien été supprimée');

        return $this->redirectToRoute('add_apikey');
    }
}
