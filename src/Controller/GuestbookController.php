<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\GuestbookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GuestbookController extends AbstractController
{
    #[Route('/guestbook', methods: ['POST', 'GET'])]
    public function index(Request $request): Response
    {
        $symfonyWayForm = $this->container->get('form.factory')->createNamed('sf_guestbook', GuestbookType::class);
        $symfonyWayForm->handleRequest($request);
        if ($symfonyWayForm->isSubmitted() && $symfonyWayForm->isValid()) {
            // Save
            return $this->redirectToRoute('app_guestbook_success');
        }

        $htmxWayForm = $this->container->get('form.factory')->createNamed('htmx_guestbook', GuestbookType::class);
        $htmxWayForm->handleRequest($request);
        if ($htmxWayForm->isSubmitted() && $htmxWayForm->isValid()) {
            // Save
            $response = $this->render('guestbook/success.html.twig');

            // Not mandatory, only to be compliant with the "old way".
            $response->headers->set('HX-Push-Url', $this->generateUrl('app_guestbook_success'));

            return $response;
        }

        return $this->render('guestbook/index.html.twig', [
            'symfonyWayForm' => $symfonyWayForm,
            'htmxWayForm' => $htmxWayForm,
        ]);
    }

    #[Route('/guestbook/success', methods: ['POST', 'GET'])]
    public function success(Request $request): Response
    {
        return $this->render('guestbook/success.html.twig');
    }
}
