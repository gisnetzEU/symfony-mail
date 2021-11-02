<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DummyController extends AbstractController
{
    
    /**
     *  @Route("/dummy/{texto}", name="dummy")
     */
    public function index(string $texto): Response{
           return new Response("Greedy method: $texto");
    }

    /**
     *  @Route("/dummy/hola", name="dummy_saluda", priority=1)
     */
    public function saluda(): Response{
        return new Response("Hola");
    }
    
}
