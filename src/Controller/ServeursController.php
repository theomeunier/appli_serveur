<?php

namespace App\Controller;

use App\Entity\Serveurs;
use App\Repository\ServeursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServeursController extends AbstractController
{
    /**
     * @Route("/serveurs", name="serveurs")
     */
    public function index(ServeursRepository $serveurs): Response
    {
        return $this->render('serveurs/index.html.twig', [
            'serveurs' => $serveurs->findAll()
        ]);
    }
    /**
     * @Route("/serveurs/{id}", name="show_serveurs")
     */
    public function show(int $id, ServeursRepository $serveurs): Response
    {
        return $this->render('serveurs/show.html.twig', [
            'serveurs' => $serveurs->find($id)
        ]);
    }
}
