<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        $success = false;
        if ($form->isSubmitted() && $form->isValid()) {
            // Ici tu peux envoyer un mail ou enregistrer le message
            $success = true;
        }

        return $this->render('pages/contact.html.twig', [
            'form' => $form->createView(),
            'success' => $success,
        ]);
    }
}