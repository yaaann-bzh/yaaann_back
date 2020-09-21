<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user/add", name="add_user")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($userPasswordEncoder->encodePassword($user, $user->getPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
    
            $this->addFlash('notice', 'Nouvel utilisateur crée, vous pouvez vous connecter');
            
            return $this->redirectToRoute('security_login');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
            'tab' => 'users'
        ]);
    }
}
