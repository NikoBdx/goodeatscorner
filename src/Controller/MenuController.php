<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ShelfRepository;
use App\Repository\FamilyRepository;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(ShelfRepository $shelfRepository): Response
    {

        $shelfs = $shelfRepository->findAll();

        return $this->render('menu/_navbar_menu.html.twig', [
            "shelfs" => $shelfs
        ]);
    }
}
