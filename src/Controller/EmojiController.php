<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmojiController extends AbstractController
{
    #[Route('/emoji')]
    public function index(Request $request): Response
    {
        $q = $request->query->get('q');

        if ($q) {
            $emojiDatabase = require __DIR__ . '/../../vendor/symfony/emoji/Resources/data/emoji-en.php';
            $results = array_filter($emojiDatabase, function ($item) use ($q) {
                return str_contains($item, mb_strtolower($q));
            });
        } else {
            $results = [];
        }


        // Here is some HTMX magic
        if ($request->headers->has('hx-request') && $request->headers->get('hx-boosted') !== 'true') {
            return $this->renderBlock('emoji/index.html.twig', 'searchResults', [
                'results' => $results,
            ]);
        }

        return $this->render('emoji/index.html.twig', [
            'results' => $results,
            'q' => $q,
        ]);
    }
}
