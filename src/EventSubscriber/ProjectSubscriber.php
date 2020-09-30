<?php

namespace App\EventSubscriber;

use App\Entity\APIUser;
use App\Entity\ProjectCathegory;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface; 
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class ProjectSubscriber implements EventSubscriberInterface
{
    private $em;
    private $author;
    private $cathegory;
    
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function onPostDeserialize(ObjectEvent $event)
    {
        $project = $event->getObject();

        $project
            ->setAuthor($this->author)
            ->setCathegory($this->cathegory);
    }

    public function OnPreDeserialize(PreDeserializeEvent $event)
    {
        $data = $event->getData();
        $this->author = $this->em->getRepository(APIUser::class)->find($data['author_id']);
        $this->cathegory = $this->em->getRepository(ProjectCathegory::class)->find($data['cathegory_id']);
    }

    public static function getSubscribedEvents()
    {
        return [
            array(
                'event' => 'serializer.post_deserialize',
                'method' => 'onPostDeserialize',
                'class' => 'App\\Entity\\Project', // if no class, subscribe to every serialization
                'format' => 'json', // optionalformat
            ),
            array(
                'event' => 'serializer.pre_deserialize',
                'method' => 'onPreDeserialize',
                'class' => 'App\\Entity\\Project', // if no class, subscribe to every serialization
                'format' => 'json', // optionalformat
            )
        ];
    }
}
