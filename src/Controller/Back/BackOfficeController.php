<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackOfficeController extends AbstractController
{
    #[Route('/back/office', name: 'app_back_office')]
    public function index(): Response
    {
        return $this->render('back/back_office/index.html.twig', [
            'controller_name' => 'BackOfficeController',
        ]);
    }
}
