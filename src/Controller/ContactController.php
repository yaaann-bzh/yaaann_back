<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Exception\ResourceValidationException;
use App\Notification\ContactNotification;
use FOS\RestBundle\Controller\AbstractFOSRestController;;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationList;

class ContactController extends AbstractFOSRestController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }
    
    /**
     * @Route(
     *      path = "/contact",
     *      name="contact",
     *      methods={"POST"}
     * )
     * 
     * @ParamConverter("contact", converter="fos_rest.request_body")
     */
    public function contactAction(Contact $contact, ConstraintViolationList $violations, ContactNotification $notification)
    {
        if (count($violations)) 
        {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ResourceValidationException($message);
        }
        
        $notification->notify($contact);

        $view = $this->view(
            array (
                'Le message a bien été envoyé. Merci !',
                $contact,
            ),
            Response::HTTP_ACCEPTED
        );

        return $this->handleView($view);
    }
}
