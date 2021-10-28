<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Pelicula;
use App\Form\PeliculaFormType;
use App\Form\PeliculaDeleteFormType;

class PeliculaController extends AbstractController
{
    /**
     * @Route("/peliculas", name="pelicula_list")
     */

    public function index(): Response
    {
        //recuperamos las pelis haciendo uso del repositorio PeliculaRepository,
        //haciendo uso del método findAll()
        $pelis = $this->getDoctrine()
            ->getRepository(Pelicula::class)
            ->findall();

        //cargamos la vista con el listado de películas y le pasamos las pelis recuperadas
        return $this->render('pelicula/list.html.twig', [
            'peliculas' => $pelis,
        ]);
    }

    /**
     * @Route("/pelicula/store", name="pelicula_store")
     */
    public function store(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $peli = new Pelicula();
        $peli->setTitulo('Avatar');
        $peli
            ->setDuracion(137)
            ->setDirector('Steven Spielberg')
            ->setGenero('Acción');

        $entityManager->persist($peli);
        $entityManager->flush();

        return new Response('Pelicula guardada con id ' . $peli->getId());
    }

    /**
     * @Route("/pelicula/{id<\d+>}", name="pelicula_show")
     */

    public function show(Pelicula $peli): Response
    {
        //retorna la respuesta (normalmente será una vista)
        return $this->render('pelicula/show.html.twig', ['pelicula' => $peli]);
    }

    /**
     * @Route("/pelicula/search/{campo}/{valor}", name="pelicula_search")
     */

    public function search($campo, $valor): Response
    {
        //recuperar las pelis
        $criterio = [$campo => $valor];
        $pelis = $this->getDoctrine()
            ->getRepository(Pelicula::class)
            ->findBy($criterio);

        //retorna la respuesta (normalmente será una vista)
        return new Response('Lista de pelis.<br>' . implode('<br>', $pelis));
    }

    /**
     * @Route("/pelicula/update/{id}")
     */

    public function update($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $peli = $entityManager->getRepository(Pelicula::class)->find($id);

        //sino existe la peli lanzamos una excepción
        if (!$peli) {
            throw $this->createNotFoundException("No se encontró la peli $id");
        }

        $peli->setTitulo('Terminator 2 - Judgment Day'); //cambiamos el título
        $entityManager->flush(); //aplicamos los cambios

        //rederigimos el método show
        return $this->redirectToRoute('pelicula_show', ['id' => $id]);
    }

    /**
     * @Route("/pelicula/destroy/{id}")
     */

    public function destroy($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $peli = $entityManager->getRepository(Pelicula::class)->find($id);

        //sino existe la peli lanzamos una excepción

        if (!$peli) {
            throw $this->createNotFoundException("No se encontró la peli $id");
        }

        $entityManager->remove($peli); //indica que hay que borrar la película
        $entityManager->flush(); //aplicamos los cambios

        //rederigimos el método show
        return new Response(
            "La pelicula <b>$peli</b> fue eliminada correctamente."
        );
    }

    /**
     * @Route("/pelicula/create", name="pelicula_create")
     */
    public function create(Request $request): Response
    {
        $peli = new Pelicula();

        $formulario = $this->createFormBuilder($peli)
            ->add('titulo', TextType::class)
            ->add('duracion', NumberType::class, [
                'empty_data' => 0,
                'html5' => true,
            ])
            ->add('director', TextType::class)
            ->add('genero', TextType::class)
            ->add('sinopsis', TextareaType::class)
            ->add('estreno', NumberType::class)
            ->add('valoracion', NumberType::class)
            ->add('Guardar', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success my-3'],
            ])
            ->getForm();

        $formulario->handleRequest($request);

        //$formulario = $this->createForm(PeliculaFormType::class, $peli);

        //si el formulario ha sido enviado y es valido

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            //almacenar los datos de la peli en la BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($peli);
            $entityManager->flush();

            //flashear el mensaje
            $this->addFlash(
                'success',
                'Pelicula guardada con id ' . $peli->getId()
            );

            return $this->redirectToRoute('pelicula_show', [
                'id' => $peli->getID(),
            ]);
        }

        //retornar la vista
        return $this->render('pelicula/create.html.twig', [
            'formulario' => $formulario->createView(),
        ]);
    }

    /**
     * @Route("/pelicula/edit/{id}", name="pelicula_edit")
     */

    public function edit(Pelicula $peli, Request $request): Response
    {
        //crea el formulario
        $formulario = $this->createForm(PeliculaFormType::class, $peli);
        $formulario->handleRequest($request);

        //si el formulario fue enviado y es válido
        if ($formulario->isSubmitted() && $formulario->isValid()) {
            //guarda los cambios en la BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush(); //ejecuta las consultas

            //flashear el mensaje
            $this->addFlash('success', 'Película actualizada correctamente');

            //redirige a "ver detalles de la peli"
            return $this->redirectToRoute('pelicula_show', [
                'id' => $peli->getId(),
            ]);
        }

        //carga la vista con el formulario
        return $this->render('pelicula/edit.html.twig', [
            'formulario' => $formulario->createView(),
            'pelicula' => $peli,
        ]);
    }

    /**
     * @Route("/pelicula/delete/{id}", name="pelicula_delete")
     */

    public function delete(Pelicula $peli, Request $request): Response
    {
        //crea el formulario
        $formulario = $this->createForm(PeliculaDeleteFormType::class, $peli);
        $formulario->handleRequest($request);

        //si el formulario fue enviado y es válido
        if ($formulario->isSubmitted() && $formulario->isValid()) {
            //guarda los cambios en la BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($peli);
            $entityManager->flush(); //ejecuta las consultas

            //flashear el mensaje
            $this->addFlash('success', 'Película eliminada correctamente');

            //redirige a la lista de peliculas
            return $this->redirectToRoute('pelicula_list');
        }

        //muestra el formulario de confirmación de borrado
        return $this->render('pelicula/delete.html.twig', [
            'formulario' => $formulario->createView(),
            'pelicula' => $peli,
        ]);
    }
}
