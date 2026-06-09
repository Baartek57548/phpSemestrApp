<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Tytuł',
                'constraints' => [
                    new NotBlank(message: 'Podaj tytuł wpisu.'),
                    new Length(min: 3, minMessage: 'Tytuł musi mieć minimum {{ limit }} znaki.'),
                ],
            ])
            ->add('excerpt', TextType::class, [
                'label' => 'Krótki opis',
                'constraints' => [
                    new NotBlank(message: 'Podaj krótki opis.'),
                    new Length(min: 5, minMessage: 'Opis musi mieć minimum {{ limit }} znaków.'),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Treść wpisu',
                'constraints' => [
                    new NotBlank(message: 'Podaj treść wpisu.'),
                    new Length(min: 10, minMessage: 'Treść musi mieć minimum {{ limit }} znaków.'),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Zapisz wpis',
            ]);
    }
}
