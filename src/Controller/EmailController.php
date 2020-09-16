<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Exception\ResourceValidationException;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use FOS\RestBundle\Controller\AbstractFOSRestController;;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class EmailController extends AbstractFOSRestController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }
    
    /**
     * @Rest\Post(
     *      path = "/contact",
     *       name="contact"
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
            $contact,
            Response::HTTP_ACCEPTED
        );

        return $this->handleView($view);
    }
}
