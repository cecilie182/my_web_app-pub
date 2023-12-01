<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoverController extends AbstractController
{
    /**
     * @Route("/", name="cover")
     */
    public function index()
    {
        return $this->render('Cover/index.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, Mailer $mailer)
    {
        $form = $this->get('form.factory')->createNamed('message', ContactType::class, null, [
            'action' => $this->generateUrl('contact'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()){
            if ($form->isSubmitted() && $form->isValid()) {
                $message = $form->getData();
                $sent = $mailer->sendMessage(
                    $message['mail'],
                    'cecilie.riviere@gmail.com',
                    'Web App contact : ' . $message['subject'],
                    $this->renderView('emails/contact.html.twig',
                        ['name' => $message['name'], 'email' => $message['mail'], 'subject' => $message['subject'], 'message' => $message['body']]
                    ));
                return new Response($sent , 200);
            }
        }

        return $this->render('Cover/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}