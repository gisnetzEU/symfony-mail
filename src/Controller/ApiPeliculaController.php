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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\XmlResponse;
use Symfony\Component\HttpFoundation\CsvResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use App\Entity\Pelicula;
use App\Entity\Actor;
use App\Kernel;


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

    /* #[Route("/ejemplo")]

    public function ejemplo():Response{
        //crea el objeto Request
        $request = Request::createFromGlobals();

        //si llega el parámetro 'nombre' por el método GET
        if($request->query->has('nombre'))
          return new Response('El nombre indicado es: '.$request->query->get('nombre'));
        
        //sino..
        else
        return new Response('No se indicó el parámetro nombre.');
    } */

    /* #[Route("/ejemplo")]

    public function ejemplo():Response{
        //crea el objeto Request
        $request = Request::createFromGlobals();

        return new Response('El nombre indicado es: '.$request->query->get('nombre', 'anónimo'));
    } */

   /*  #[Route("/ejemplo")]

    public function ejemplo(Request $request):Response{

        //crea una petición simulada
        $request = Request::create(
            'holamundo',
            'GET',
            ['nombre' => 'Robert']
        );

        $request->overrideGlobals(); //reescribe las superglobales de PHP

        $texto = 'El nombre es: '.$request->query->get('nombre');
        $texto .= ' y si lo miramos en $_GET: '.$_GET['nombre'];

        return new Response($texto);
    } */

    //Ejemplo que funciona con postman
    #[Route("/ejemplo")]

    public function ejemplo(Request $request):Response{

    //convierte la información de JSON a array
    $datos = $request->toArray();

    //recupera alguno de los campos y lo muestra
    return new Response('El nombre es '.$datos['nombre']);
    }

    #[Route("/api/pelicula/create")]

    public function create(Request $request):Response{
     //preparar el serializador
     $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

     //recuperar los datos en JSON y pasarlos a la Película
     $peli = $serializer->deserialize($request->getContent(), 'App\Entity\Pelicula','json');

     //almacenar los datos de la peli en la BDD
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->persist($peli); //indica a doctrine que queremos guardar la peli
     $entityManager->flush();  //ejecuta las consultas

     //retornar una respuesta JSON
     $respuesta = new \stdClass();
     $respuesta->status = 'OK';

     return new JsonResponse($respuesta);
    }

    /* #[Route("/api/pelicula/create/xml")]

    public function create(Request $request):Response{
     //preparar el serializador
     $serializer = new Serializer([new ObjectNormalizer()], [new XmlEncoder()]);

     //recuperar los datos en JSON y pasarlos a la Película
     $peli = $serializer->deserialize($request->getContent(), 'App\Entity\Pelicula','xml');


     //almacenar los datos de la peli en la BDD
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->persist($peli); //indica a doctrine que queremos guardar la peli
     $entityManager->flush();  //ejecuta las consultas

     //retornar una respuesta JSON
     $respuesta = new \stdClass();
     $respuesta->status = 'OK';

     return new JsonResponse($respuesta);
    } */

    /* #[Route("/api/pelicula/create/csv")]

    public function create(Request $request):Response{
     //preparar el serializador
     $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

     //recuperar los datos en JSON y pasarlos a la Película
     $peli = $serializer->deserialize($request->getContent(), 'App\Entity\Pelicula','csv');

     dd($peli);

     //almacenar los datos de la peli en la BDD
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->persist($peli); //indica a doctrine que queremos guardar la peli
     $entityManager->flush();  //ejecuta las consultas

     //retornar una respuesta JSON
     $respuesta = new \stdClass();
     $respuesta->status = 'OK';

     return new JsonResponse($respuesta);
    } */


    #[Route("/getcookie")]
    
    public function getcookie(Request $request):Response{

        return $request->cookies->has('autor') ?
          new Response("He recuperado: ".$request->cookies->get('autor')) :
          new Response("No existe la cookie con nombre 'autor'.");
    }

    #[Route('/api/actor', name: 'actores_json')]
    
    public function actoresJson(): Response{

        //recuperar los actores
        $actores = $this->getDoctrine()->getRepository(Actor::class)->findAll();

        //preparar el serializador
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        //normalizar y serializar la respuesta en JSON
        $contenido = $serializer->serialize($actores, 'json');

        //crear la respuesta y establecer el Content-Type
        $response = new Response($contenido);
        $response->headers->set('Content-Type', 'application/json');

        //retorna la respuesta en JSON con los resultados
        return $response;
    }
    
}
