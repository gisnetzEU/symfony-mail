<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}