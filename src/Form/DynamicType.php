<?php

namespace App\Form;

use App\Enum\Food;
use App\Enum\Meal;
use App\Enum\PizzaSize;
use App\Model\MealPlan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Adaptation of https://github.com/symfony/ux/blob/2.x/ux.symfony.com/src/Form/MealPlannerForm.php.
 *
 * DynamicFormBuilder with classic Symfony + HTMX.
 */
class DynamicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('meal', EnumType::class, [
                'class' => Meal::class,
                'choice_label' => fn (Meal $meal): string => $meal->getReadable(),
                'placeholder' => 'Which meal is it?',
            ])
        ;

        $this->initializeListeners($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => MealPlan::class]);
    }

    private function initializeListeners($builder): void
    {
        $formModifierPizza = function (FormInterface $form, ?Food $food = null) use ($builder): void {
            if ($food == Food::Pizza) {
                $builder->add('pizzaSize', EnumType::class, [
                    'class' => PizzaSize::class,
                    'placeholder' => 'What size pizza?',
                    'choice_label' => fn(PizzaSize $pizzaSize): string => $pizzaSize->getReadable(),
                    'required' => true,
                ]);
                $field = $builder->get('pizzaSize')->setAutoInitialize(false)->getForm();
                $form->add($field);
            }
        };

        $formModifierFood = function (FormInterface $form, ?Meal $meal = null) use ($builder, $formModifierPizza): void {
            $builder->add('mainFood', EnumType::class, [
                'class' => Food::class,
                'placeholder' => null === $meal ? 'Select a meal first' : \sprintf('What\'s for %s?', $meal->getReadable()),
                'choices' => $meal?->getFoodChoices(),
                'choice_label' => fn (Food $food): string => $food->getReadable(),
                'disabled' => null === $meal,
            ]);

            // auto initialize mimics FormBuilder::getForm() behavior
            $field = $builder->get('mainFood')->setAutoInitialize(false)->getForm();
            $form->add($field);

            $builder->get('mainFood')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifierPizza): void {
                    $formModifierPizza($event->getForm()->getParent(), $event->getForm()->getData());
                }
            );
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifierFood, $formModifierPizza): void {
                $formModifierFood($event->getForm(), $event->getData()?->getMeal());
                $formModifierPizza($event->getForm(), $event->getData()?->getMainFood());
            }
        );

        $builder->get('meal')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifierFood): void {
                $formModifierFood($event->getForm()->getParent(), $event->getForm()->getData());
            }
        );
    }
}