<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTAuthenticationSuccessListener
{
    private $tokenTTL;

    public function __construct($tokenTTL) {
        $this->tokenTTL = $tokenTTL;
    }

    /**
     * @Param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceOf UserInterface) {
            return;
        }

        $expirationDate = new \DateTime();
        $expirationDate->setTimestamp($expirationDate->getTimestamp() + $this->tokenTTL);

        $data['userId'] = $user->getId();
        $data['expirationDate'] = $expirationDate->format('Y-m-d\TH:i:s');

        $event->setData($data);
    }
}
