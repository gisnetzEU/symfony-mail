<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Pelicula;

class PeliculaController extends AbstractController
{
    /**
     * @Route("/peliculas", name="pelicula_list")
     */
             
    public function index():Response
    {
        //recuperamos las pelis haciendo uso del repositorio PeliculaRepository,
        //haciendo uso del método findAll()
        $pelis = $this->getDoctrine()->getRepository(Pelicula::class)->findall();
        
        //cargamos la vista con el listado de películas y le pasamos las pelis recuperadas
        return $this->render("pelicula/list.html.twig", ["peliculas" => $pelis]);
        
    }
    /*
     public function index():Response
    {
        //recuperar las pelis
        //el método findAll retorna un array de objetos Pelicula
        $pelis = $this->getDoctrine()->getRepository(Pelicula::class)->findall();
        
        //retorna la respuesta (normalmente será una vista)
        return new Response("Lista de pelis.<br>".implode("<br>",$pelis));
        
    }
    */
    
    /**
     * @Route("/pelicula/store", name="pelicula_store")
     */
    public function store():Response{
      $entityManager = $this->getDoctrine()->getManager();
      
      $peli = new Pelicula();
      $peli->setTitulo('Avatar');
      $peli->setDuracion(137)->setDirector('Steven Spielberg')->setGenero('Acción');
      
      $entityManager->persist($peli);
      $entityManager->flush();
    
      return new Response('Pelicula guardada con id '.$peli->getId());
    }
    
    /**
     * @Route("/pelicula/{id<\d+>}", name="pelicula_show")
     */
    
    public function show(Pelicula $peli):Response{
        //retorna la respuesta (normalmente será una vista)
        return $this->render("pelicula/show.html.twig", ["pelicula"=>$peli]);
    }
    
    /*("/pelicula/{id}", name="pelicula_show")
     * public function show(Pelicula $peli):Response{
        //retorna la respuesta (normalmente será una vista)
        return new Response("Información de la película: $peli");        
    }*/
    
   /* public function show($id):Response{
        //recuperar la peli
        $peli = $this->getDoctrine()->getRepository(Pelicula::class)->find($id);
        
        //sino existe la peli lanzamos una excepción
        if(!$peli)
            throw $this->createNotFoundException("No se encontró la peli $id");
        
        //retorna la respuesta(normalmente será una vista)
        return new Response("Información de la película: $peli");   
     }*/
        
        /**
         * @Route("/pelicula/search/{campo}/{valor}", name="pelicula_search")
         */
        
        public function search($campo, $valor):Response{
            //recuperar las pelis
            $criterio = [$campo=>$valor];
            $pelis = $this->getDoctrine()->getRepository(Pelicula::class)->findBy($criterio);

            //retorna la respuesta (normalmente será una vista)
            return new Response("Lista de pelis.<br>".implode("<br>",$pelis));
        }
        
        /**
         * @Route("/pelicula/update/{id}")
         */
        
        public function update($id):Response{            
            $entityManager = $this->getDoctrine()->getManager();
            $peli = $entityManager->getRepository(Pelicula::class)->find($id);
            
            //sino existe la peli lanzamos una excepción            
            if (!$peli)
                throw $this->createNotFoundException("No se encontró la peli $id");
            
            $peli->setTitulo('Terminator 2 - Judgment Day'); //cambiamos el título
            $entityManager->flush(); //aplicamos los cambios
            
            //rederigimos el método show
            return $this->redirectToRoute('pelicula_show', ['id' => $id]);            
        }
        
        /*public function update(Pelicula $peli):Response{
            //retorna la respuesta (normalmente será una vista)
            
            $peli->setTitulo('Terminator II - Judgment Day'); //cambiamos el título
            $entityManager->flush(); //aplicamos los cambios
            
            //rederigimos el método show
            return $this->redirectToRoute('pelicula_show', ['id' => $id]);        
                        
        }*/
        
        /**
         * @Route("/pelicula/destroy/{id}")
         */
        
        public function destroy($id):Response{            
            $entityManager = $this->getDoctrine()->getManager();
            $peli = $entityManager->getRepository(Pelicula::class)->find($id);
            
            //sino existe la peli lanzamos una excepción
            
            if (!$peli)
                throw $this->createNotFoundException("No se encontró la peli $id");
                
                $entityManager->remove($peli); //indica que hay que borrar la película
                $entityManager->flush(); //aplicamos los cambios
                
                //rederigimos el método show
                return new Response("La pelicula <b>$peli</b> fue eliminada correctamente.");
                
        }
        
}