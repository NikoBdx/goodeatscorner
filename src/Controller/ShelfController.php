<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShelfController extends AbstractController
{
    #[Route('/shelf', name: 'app_shelf')]
    public function index(): Response
    {
        return $this->render('shelf/index.html.twig', [
            'controller_name' => 'ShelfController',
        ]);
    }
}
