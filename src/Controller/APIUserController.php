<?php

namespace App\Controller;

use App\Entity\APIUser;
use App\Form\APIUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class APIUserController extends AbstractController
{
    /**
     * @Route("/admin/apiuser", name="add_apiuser")
     */
    public function index(Request $request)
    {
        $apiUser = new APIUser();
        $form = $this->createForm(APIUserType::class, $apiUser);
        $form->handleRequest($request);
        $apiUser
            ->setApiToken(bin2hex(random_bytes(15)))
            ->setCreationDate(new \DateTime());

        $repository = $this->getDoctrine()->getRepository(APIUser::class);
        $apiUsers = $repository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($apiUser);
            $em->flush();

            return $this->redirectToRoute('show_apiuser', array(
                'id' => $apiUser->getId())
            );
        }

        return $this->render('apiuser/form.html.twig', [
            'form' => $form->createView(),
            'apiusers' => $apiUsers,
            'tab' => 'apiuser'
        ]);
    }

    /**
     * @Route("/admin/apiuser/{id}", name="show_apiuser")
     * @ParamConverter("apiUser", class="App\Entity\ApiUser")
     */
    public function show(APIUser $apiUser)
    {
        $repository = $this->getDoctrine()->getRepository(APIUser::class);
        $apiUsers = $repository->findAll();

        return $this->render('apiuser/created.html.twig', [
            'apiuser' => $apiUser,
            'apiusers' => $apiUsers,
            'tab' => 'apiuser'
        ]);
    }

    /**
     * @Route("/admin/apiuser/delete/{id}", name="delete_apiuser")
     * @ParamConverter("apiUser", class="App\Entity\ApiUser")
     */
    public function delete(APIUser $apiUser)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($apiUser);
        $em->flush();

        $this->addFlash('notice', 'La clé a bien été supprimée');

        return $this->redirectToRoute('add_apiuser');
    }
}
