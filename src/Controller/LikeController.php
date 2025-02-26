<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Emoji\EmojiTransliterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LikeController extends AbstractController
{
    #[Route('/like')]
    public function index(Request $request): Response
    {
        return $this->render('like/index.html.twig', [
            'isLiked' => $request->getSession()->get('isLiked', false),
        ]);
    }

    #[Route('/like/toggle', methods: ['POST'])]
    public function toggle(Request $request): Response
    {
        $request->getSession()->set(
            'isLiked',
            !$request->getSession()->get('isLiked', false)
        );

        return $this->renderBlock('like/index.html.twig', 'like', [
            'isLiked' => $request->getSession()->get('isLiked', false),
        ]);
    }
}
