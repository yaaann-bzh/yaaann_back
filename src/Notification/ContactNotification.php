<?php

namespace App\Notification;

use App\Entity\Contact;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ContactNotification  
{
    private $mailer;
    private $renderer;

    public function __construct(MailerInterface $mailer, Environment $renderer) 
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact)
    {
        $message = (new Email())
            ->from('noreply@yaaann.ovh')
            ->replyTo($contact->getEmail())
            ->to('yann.tachier@gmail.com')
            ->subject('Nouveau contact de ' . $contact->getfullname() . ' depuis yaaann.fr')
            ->html($this->renderer->render('emails/contact.html.twig', array(
                'contact' => $contact
            )), 'text/html');

        $this->mailer->send($message);
    }
}
