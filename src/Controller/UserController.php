<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/adduser", name="add_user")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $user = new User();
        $user->setEmail('me@yaaann.ovh');
        $user->setUsername('yaaann');
        $user->setRoles([
            'ROLE_USER',
            'ROLE_ADMIN',
            'ROLE_ALLOWED_TO_SWITCH'
        ]);
        $user->setPassword($userPasswordEncoder->encodePassword($user, '123456'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('notice', 'Nouvel utilisateur crée, vous pouvez vous connecter');
        
        return $this->redirectToRoute('security_login');
    }
}
