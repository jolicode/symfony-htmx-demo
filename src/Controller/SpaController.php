<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpaController extends AbstractController
{
    #[Route('/', name: 'app')]
    public function index(): Response
    {
        return $this->render('spa/index.html.twig', [
            'controller_name' => 'SpaController',
        ]);
    }

    #[Route('/new-page', name: 'app_new_page')]
    public function newPage(): Response
    {
        return $this->render('spa/new_page.html.twig');
    }

    #[Route('/message', name: 'app_spa_message')]
    public function message(Request $request, EntityManagerInterface $em): Response
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message, [
            'action' => $this->generateUrl('app_spa_message'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($message);
            $em->flush();

            return $this->render('spa/messages/message.html.twig', [
                'form' => $form->createView(),
                'submitted' => true,
                'message' => $message,
            ]);
        }

        return $this->render('spa/messages/message.html.twig', [
            'form' => $form->createView(),
        ]);
    }}
