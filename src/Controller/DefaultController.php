<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use App\Form\ContactoFormType;

class DefaultController extends AbstractController
{
    /**
     * Método index
     * @Route("/", name="portada")
     * @return \Symfony\Component\HttpFoundation\Response
     */
         
    public function index():Response{
      return $this->render("portada.html.twig");
    }

    /**
     * @Route("/contact", name="contacto")
     */

    public function contacto(Request $request, MailerInterface $mailer):Response{

      //crea el formulario
      $formulario = $this->createForm(ContactoFormType::class);
      $formulario->handleRequest($request);

      //si el formulario fue enviado y es válido
      if($formulario->isSubmitted() && $formulario->isValid()){

        //tomar los parámetro que lleguen por POST
        $nombre = $request->request->get('contacto_form')['nombre'];
        $asunto = $request->request->get('contacto_form')['asunto'];
        $mensaje = $request->request->get('contacto_form')['mensaje'];
        $de = $request->request->get('contacto_form')['email'];

        //preparar el email
        $email = (new Email())
          ->from($de)
          ->to($this->getParameter('app.admin_email'))
          ->subject($asunto)
          ->text($mensaje);

        //enviar el email
        try {
          $mailer->send($email);

          //flashear mensaje y redirigir a la portada
          $this->addFlash('success', 'Mensaje enviado correctamente');
        } catch (TransportExceptionInterface $e) {
          //flashear mensaje y redirigir a la portada
          $this->addFlash('error', 'Ha habido un error');
          echo("<pre>$e</pre>");

          // some error prevented the email sending; display an
          // error message or try to resend the message
        }

        
        
        return $this->redirectToRoute('portada');

      }

      //muestra la vista con el formulario
      return $this->render("contacto.html.twig", ["formulario"=>$formulario->createView()]);

  }
}