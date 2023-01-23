<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\AlertServiceInterface;
use App\Service\FileUploadServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private AlertServiceInterface $alertService;

    public function __construct( AlertServiceInterface $alertService)
    {
        $this->alertService = $alertService;
    }

    #[Route('/contact', name:'contact')]
    public function index(Request $request, MailerInterface $mailer):Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();

            $message = (new Email())
                ->from($this->getParameter('sender.email'))
                ->to($this->getParameter('receiver.email'))
                ->subject('Contact web')
                ->text('Depuis : '.$contactFormData['email'].\PHP_EOL.
                    $contactFormData['message'],
                    'text/plain');
            try{
                $mailer->send($message);
                $this->alertService->info('Votre message à bien été envoyé.');

            }catch (\Exception $e){
                $this->alertService->danger('Votre message n\' a pas pu  bien être envoyé. Envoyez votre mail sur contact.ronde-lingons.fr');

            }

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
