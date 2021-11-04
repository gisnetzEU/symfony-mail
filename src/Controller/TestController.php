<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestController extends AbstractController
{
   
    #[Route('/ejemplo')]
    public function ejemplo():Response{

        //crea una respuesta
        $response = new Response(
            'Esta es la respuesta',
            Response::HTTP_OK,
            ['content-type'=>'text/html']
        );

        return $response->setCharset('ISO-8859-1');
    }

    #[Route('/out')]
    public function out():Response{

        $response = new RedirectResponse('https://juegayestudia.com');

        return $response;

    }

    #[Route('/ejemplojson')]
    public function ejemplojson():Response{

        //crea un objeto stdClass y le coloco algunos datos
        $persona = new \stdClass();
        $persona->nombre = "Giselle";
        $persona->edad = 39;
        $persona->poblacion = "Puebla";

        //preparo la respuesta con el contenido en JSON y el header adecuado
        $response = new Response();
        $response->setContent(json_encode($persona));
        $response->headers->set('Content-Type', 'application/json');

        //retorno la respuesta 
        return $response;
       
    }

    #[Route('/ejemplojson2')]
    public function ejemplojson2():Response{

        //crea un objeto stdClass y le coloco algunos datos
        $persona = new \stdClass();
        $persona->nombre = "Giselle2";
        $persona->edad = 39;
        $persona->poblacion = "Puebla";

        return new JsonResponse($persona);
    }

    #[Route('/test', name: 'test')]
    public function index(SessionInterface $session): Response{
        
        $projectDir = $this->getParameter('kernel.project_dir');
        $adminEmail = $this->getParameter('app.admin_email');

        return new Response("$projectDir - $adminEmail");;
    
    }

    /* #[Route('/test', name: 'test')]
    public function index(SessionInterface $session): Response{        
        
        //flasheando datos a la sesión
        $this->addFlash('success', 'Así se flashea info a la sesión');
        $session->getFlashBag()->add('success', 'Así también');

        //guardando datos en la sesión
        $session->set('nombre', 'Giselle');

        dd($session);

        return new Response('Test');
    }  */
    
   /*  #[Route('/test', name: 'test')]
    public function index(Request $request): Response{        
        dd($request);
        return new Response('Test');
    }  */

    /* #[Route('/test/{id}', name: 'test')]
    public function index($id): Response{     
        
        if($id == 1)
          throw new HttpException(500, 'Lanzando una excepción (500)');
    
        if($id == 2)
          throw new NotFoundHttpException('No se ha encontrado (404)');

        // if($id == 3)
        //   throw $this->createNotFoundHttpException('Not Found (404)');

        return new Response("No se lanzó ninguna excepción");
    }  */

    /* #[Route('/test', name: 'test')]
    public function index(): Response{        
        return $this->redirect('/peliculas');
    } */

    /* #[Route('/test', name: 'test')]
    public function index(): Response{
        $ruta = $this->generateUrl('pelicula_show', ['id'=>3]);
        return new Response("Ejemplo de ruta generada: $ruta");
    } */

    /*
    #[Route('/test', name: 'test')]
    public function index(): Response{
        
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    */
}
