<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\HttpFoundation\Cookie;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

use App\Entity\Pelicula;
use App\Kernel;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ApiPeliculaController extends AbstractController{

    #[Route('/api/pelicula', name: 'peliculas_json')]
    
    public function pelisJson(): Response{

        //recuperar las pelis
        $pelis = $this->getDoctrine()->getRepository(Pelicula::class)->findAll();

        //preparar el serializador
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        //normalizar y serializar la respuesta en JSON
        $contenido = $serializer->serialize($pelis, 'json');

        //crear la respuesta y establecer el Content-Type
        $response = new Response($contenido);
        $response->headers->set('Content-Type', 'application/json');

        //retorna la respuesta en JSON con los resultados
        return $response;
    }

    #[Route('/api/peliculas/{formato}', name: 'api_peliculas')]
    
    public function pelis(string $formato = 'json'): Response{

        //recuperar las pelis
        $pelis = $this->getDoctrine()->getRepository(Pelicula::class)->findAll();

        //preparar el serializador
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder(), new XmlEncoder(), new CsvEncoder()]);

        //normalizar y serializar la respuesta 
        $formtato = strtoLower($formato);

        try{
            $contenido = $serializer->serialize($pelis, $formato);
        }catch(NotEncodableValueException $e){
            return new Response('Formato no válido');
        }    

        //crear la respuesta y establecer el Content-Type
        $response = new Response($contenido);

        switch($formato){
            case 'json' : $formato = 'application/json'; break;
            case 'xml' : $formato = 'text/xml'; break;
            case 'csv' : $formato = 'text/csv'; break;            
            default : $formato = 'text/plain';
        }

        $response->headers->set('Content-Type', $formato);

        //retorna la respuesta con los resultados en el formato deseado
        return $response;
    }

    #[Route("/sendcookie")]
    
    public function sendCookie():Response{

        $cookie = Cookie::create('autor')
          ->withValue('Giselle')
          ->withValue(strtotime('Fri, 01-Oct-2031 10:00:00 GMT'))
          ->withSecure(false);

        //crea una respuesta
        $response = new Response(
            'Esta es la respuesta',
            Response::HTTP_OK,
            ['content-type'=>'text/html']
        );

        $response->headers->setCookie($cookie);

        return $response;
    }

    /* #[Route("/brochure")]
    
    public function brochure():Response{
        return new BinaryFileResponse('pdf/brochure.pdf');
    } */

    /* #[Route("/brochure")]
    
    public function brochure():Response{
        return new BinaryFileResponse(__DIR__.'../../../pdf/brochure.pdf');
    } */

  
    /* #[Route("/brochure")]
    
    public function brochure(Kernel $kernel):Response{
        //recupera la raiz del proyecto
        $raiz = $kernel->getProjectDir();

        //calcula la ruta a partir de la raiz
        return new BinaryFileResponse($raiz.'/pdf/brochure.pdf');
    } */

   /*  #[Route("/brochure")]
    
    public function brochure(Kernel $kernel):Response{
        //recupera la raiz del proyecto
        $raiz = $kernel->getProjectDir();

        //calcula la ruta a partir de la raiz
        $response = new BinaryFileResponse($raiz.'/pdf/brochure.pdf');

        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }  */

    #[Route("/brochure")]
    
    public function brochure(Kernel $kernel):Response{
        //recupera la raiz del proyecto
        $raiz = $kernel->getProjectDir();

        //calcula la ruta a partir de la raiz
        $response = new BinaryFileResponse($raiz.'/pdf/brochure.pdf');

        $response->headers->set('Content-Type', 'application/pdf');

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'panfleto.pdf');

        return $response;
    } 


}
