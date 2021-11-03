<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TestController extends AbstractController
{
   
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
