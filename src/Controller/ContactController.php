<?php

namespace App\Controller;

use App\Entity\ContactRequest;
use App\Entity\ContactUser;
use App\Form\ContactRequestType;
use App\Service\JsonUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ContactController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly JsonUploaderService $jsonUp
    )
    {}

    #[Route('/', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $requestContact = new ContactRequest();
        $form = $this->createForm(ContactRequestType::class, $requestContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactUserRequest = $requestContact->getContactUser();
            $contactUser = $this->em->getRepository(ContactUser::class)->findOneBy(['email' => $contactUserRequest?->getEmail() ]);
            if ($contactUser instanceof ContactUser) {
                $requestContact->setContactUser($contactUser);
            } else {
                $this->em->persist($contactUserRequest);
            }

            $this->em->persist($requestContact);
            $this->em->flush();
            $jsonData = $this->json($requestContact,
                200,
                [],
                [
                    'groups' => ['json_create']
                ]);
            $this->jsonUp->uploadJson($jsonData, $requestContact->getId());

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
