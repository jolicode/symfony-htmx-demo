<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $em->persist($message);
            $em->flush();

            $this->addFlash('success', sprintf('Message "%s" sent!', $message->getContent()));

            return $this->render('spa/messages/message.html.twig', [
                'form' => $form->createView(),
                'submitted' => true,
            ]);
        }

        return $this->render('spa/messages/message.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/messages', name: 'app_spa_messages')]
    public function messages(): Response
    {
        return $this->render('spa/messages/messages.html.twig');
    }

    #[Route('/search/message', name: 'app_spa_search_message', methods: ['GET'])]
    public function searchMessage(Request $request, MessageRepository $messageRepository): Response
    {
        $search = $request->get('search');

        return $this->render('spa/messages/message_list.html.twig', [
            'messages' => $messageRepository->getByContentLike($search),
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): never
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/_/messages/list', name: 'app_spa_message_list')]
    public function messageList(MessageRepository $messageRepository): Response
    {
        return $this->render('spa/messages/message_list.html.twig', [
            'messages' => $messageRepository->findAll(),
        ]);
    }
}
