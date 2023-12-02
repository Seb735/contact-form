<?php

namespace App\Controller;

use App\Entity\ContactRequest;
use App\Form\ContactType;
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
        private readonly SerializerInterface $serializer,
        private readonly JsonUploaderService $jsonUp
    )
    {}

    #[Route('/', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $requestContact = new ContactRequest();
        $form = $this->createForm(ContactType::class, $requestContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($requestContact);
            $this->em->flush();
            $jsonData = $this->serializer->serialize($requestContact, JsonEncoder::FORMAT);
            $this->jsonUp->uploadJson($jsonData, $requestContact->getId());

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
