<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $message = "";
        $email = (new Email())
            ->from(new Address('fabien@example.com', 'Fabien'))
            ->to('go@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!');
            try 
            {

                $mailer->send($email);
                $message = "Mail Sent Successfully"; 
                
            } 
            catch (TransportExceptionInterface $e) 
            {

                $message = $e->getMessage(); 

            }         

        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
            'message' => $message,
        ]);
    }
}
