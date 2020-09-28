<?php

namespace App\Security;

use App\Entity\APIUser;
use App\Service\ConnectionLimit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ContactAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $contactLimit;
    private $connectionLimit;

    public function __construct(EntityManagerInterface $em, ConnectionLimit $connectionLimit, int $contactLimit)
    {
        $this->em = $em;
        $this->contactLimit = $contactLimit;
        $this->connectionLimit = $connectionLimit;
    }

    public function supports(Request $request)
    {
        return $request->query->has('apikey');
    }

    public function getCredentials(Request $request)
    {
        return $request->query->get('apikey');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (null === $credentials) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            return new Response("Authentication Failed", Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->em->getRepository(APIUser::class)->findOneBy(['apiToken' => $credentials]);

        // if a User is returned, checkCredentials() is called
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->connectionLimit->checkLimit($user, $this->contactLimit);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentification requise'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
