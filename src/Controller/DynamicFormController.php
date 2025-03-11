<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\Food;
use App\Enum\Meal;
use App\Enum\PizzaSize;
use App\Form\DynamicType;
use App\Model\MealPlan;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DynamicFormController extends AbstractController
{
    #[Route('/dynamic-form')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(DynamicType::class, new MealPlan());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /*
            $response = $this->renderBlock('dynamic/index.html.twig', 'result', [
                'form' => $form,
                'mealPlan' => $form->getData(),
            ]);
            $response->headers->set('HX-Retarget', '#result');
            */

//            return $response;
        }

        // Here is some HTMX magic
        if ($request->headers->has('hx-request') && $request->isMethod('POST')) {
            return $this->renderBlock('dynamic/index.html.twig', 'form', [
                'form' => $form,
                'mealPlan' => $form->getData(),
            ]);
        }

        return $this->render('dynamic/index.html.twig', [
            'form' => $form,
            'mealPlan' => $form->getData(),
        ]);
    }
}
