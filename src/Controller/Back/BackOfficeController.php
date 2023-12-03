<?php

namespace App\Controller\Back;

use App\Entity\ContactRequest;
use App\Entity\ContactUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackOfficeController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {}

    #[Route('/back/list', name: 'app_back_list')]
    public function list(): Response
    {
        $repoContactUser = $this->em->getRepository(ContactUser::class);

        $contactUser = $repoContactUser->findAll();
        dump($contactUser);
        return $this->render('back/back_office/list.html.twig', [
            'contactUsers' => $contactUser,
        ]);
    }

    #[Route('/back/check/{id}', name: 'app_back_check')]
    public function check(ContactRequest $contactRequest): Response
    {
        $contactRequest->setChecked(!$contactRequest->isChecked());
        $this->em->flush();
        return $this->redirectToRoute('app_back_list');
    }
}
