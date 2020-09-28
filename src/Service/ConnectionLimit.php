<?php

namespace App\Service;

use App\Entity\APIUser;
use App\Entity\UserConnection;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ConnectionLimit  
{
    private $em;
    
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function checkLimit(APIUser $user, int $limit)
    {
        $nb = $this->em->getRepository(UserConnection::class)->count(['user' => $user]);

        if ($nb < $limit) {
            $this->addNewConnection($user);
            $this->em->flush();
            return true;
        } 

        $connections = $this->getList($user, $limit);

        return $this->compareDateTime($connections[$limit-1], $user);             
    }

    public function compareDateTime(UserConnection $oldestConnection, APIUser $user)
    {
        $now = new DateTime();
        $oldestTimestamp = $oldestConnection->getConnectDate()->getTimestamp();

        if ($now->getTimestamp() - $oldestTimestamp > 3600) {
            $this->em->remove($oldestConnection);
            $this->addNewConnection($user);
            $this->em->flush();
            return true;
        }

        $nextConnectionDate = new DateTime();
        $nextConnectionDate->setTimestamp($oldestTimestamp + 3600);

        throw new TooManyRequestsHttpException(null, 'Too many requests for the last hour, you should retry after ' . $nextConnectionDate->format('H:i:s'));
    }

    public function getList(APIUser $user, $limit)
    {
        $userConnections = $this->em->getRepository(UserConnection::class)->findBy(
            ['user' => $user],
            ['connectDate' => 'DESC']
        );

        if (count($userConnections) > $limit) {
            $userConnections = $this->clearEntities($userConnections, $limit);
        }
        
        return $userConnections;
    }

    public function clearEntities($userConnections, $limit)
    {
        for ($i=$limit; $i < count($userConnections); $i++) { 
            $this->em->remove($userConnections[$i]);
        }

        $this->em->flush();

        return array_slice($userConnections, 0, $limit);
    }

    public function addNewConnection(APIUser $user)
    {
        $newConnection = new UserConnection();
        $newConnection
                ->setUser($user)
                ->setConnectDate(new DateTime());
        $this->em->persist($newConnection);
    }

}
